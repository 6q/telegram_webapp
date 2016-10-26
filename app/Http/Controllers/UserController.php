<?php namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\UserBillingRepository;
use App\Repositories\RoleRepository;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\RoleRequest;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\UserTransaction;
use App\Models\Country;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class UserController extends Controller {

	/**
	 * The UserRepository instance.
	 *
	 * @var App\Repositories\UserRepository
	 */
	protected $user_gestion;

	/**
	 * The RoleRepository instance.
	 *
	 * @var App\Repositories\RoleRepository
	 */	
	protected $role_gestion;
	
	protected $userbilling_gestion;

	/**
	 * Create a new UserController instance.
	 *
	 * @param  App\Repositories\UserRepository $user_gestion
	 * @param  App\Repositories\RoleRepository $role_gestion
	 * @return void
	 */
	public function __construct(
		UserRepository $user_gestion,
		RoleRepository $role_gestion,
		UserBillingRepository $userbilling_gestion)
	{
	
		$this->user_gestion = $user_gestion;
		$this->role_gestion = $role_gestion;
		$this->userbilling_gestion = $userbilling_gestion;
       
		$this->middleware('user', ['except' => ['index', 'show', 'indexSort', 'create','store','edit','update','destroy','getRoles','postRoles']]);
		
	
		$this->middleware('admin');
		
		$this->middleware('ajax', ['only' => 'updateSeen']);
	}
	
	public function dashboard()
	{
	     echo 'as';
	exit;
		return view('back.users.index');	
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return $this->indexSort('total');
	}

	/**
	 * Display a listing of the resource.
	 *
     * @param  string  $role
	 * @return Response
	 */
	public function indexSort($role)
	{
		$counts = $this->user_gestion->counts();
		$users = $this->user_gestion->index(4, $role);
		
		$links = $users->render();
		$roles = $this->role_gestion->all();

		return view('back.users.index', compact('users', 'links', 'counts', 'roles'));		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('back.users.create', $this->role_gestion->getAllSelect());
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  App\requests\UserCreateRequest $request
	 *
	 * @return Response
	 */
	public function store(
		UserCreateRequest $request)
	{
		$this->user_gestion->store($request->all());

		return redirect('user')->with('ok', trans('back/users.created'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  App\Models\User
	 * @return Response
	 */
	public function show(User $user)
	{
	   
		$user_billings = $this->userbilling_gestion->all($user->id);
		
		$user_subscriptions = UserSubscription::with('plan')->where('user_id', $user->id)->get();
		
		$user_transactions = UserTransaction::with('plan')->where('user_id', $user->id)->get();
	
		return view('back.users.show',  compact('user','user_billings','user_subscriptions','user_transactions'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  App\Models\User
	 * @return Response
	 */
	public function edit(User $user)
	{
	   
		$country = Country::all()->pluck('name', 'id');
		
		return view('back.users.edit', array_merge(compact('user','country'), $this->role_gestion->getAllSelect()));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  App\requests\UserUpdateRequest $request
	 * @param  App\Models\User
	 * @return Response
	 */
	public function update(
		UserUpdateRequest $request,
		User $user)
	{
	    if($request->hasFile('image'))
		{
			
			$error =$_FILES["image"]["error"];
			
			$file = Input::file('image');
            //getting timestamp
			$ext=@end(@explode(".",$file->getClientOriginalName()));
			
            $name = time().".".$ext;
          
            $file->move(public_path().'/user_images/', $name);
		    $user->image=$name;
		 }
		 
	
		$this->user_gestion->update($request->all(), $user);

		return redirect('user')->with('ok', trans('back/users.updated'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Illuminate\Http\Request $request
	 * @param  App\Models\User $user
	 * @return Response
	 */
	public function updateSeen(
		Request $request, 
		User $user)
	{
		$this->user_gestion->update($request->all(), $user);

		return response()->json();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  App\Models\user $user
	 * @return Response
	 */
	public function destroy(User $user)
	{
		$this->user_gestion->destroyUser($user);

		return redirect('user')->with('ok', trans('back/users.destroyed'));
	}

	/**
	 * Display the roles form
	 *
	 * @return Response
	 */
	public function getRoles()
	{
		$roles = $this->role_gestion->all();

		return view('back.users.roles', compact('roles'));
	}

	/**
	 * Update roles
	 *
	 * @param  App\requests\RoleRequest $request
	 * @return Response
	 */
	public function postRoles(RoleRequest $request)
	{
		$this->role_gestion->update($request->except('_token'));
		
		return redirect('user/roles')->with('ok', trans('back/roles.ok'));
	}
	
}
