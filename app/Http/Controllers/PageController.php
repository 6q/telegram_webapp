<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Contracts\Auth\User;
use Auth;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Requests\PageCreateRequest;
use App\Models\Page;

class PageController extends Controller
{
    public function __construct() {
        parent::getTotalbot_chanel();
        
    }
    
    public function index(){
        $pages = Page::latest()->paginate(20);
		$links = $pages->render();
		return view('back.pages.index', compact('pages','links'));	
    }
    
    public function show(Page $page)
	{
        return view('back.pages.show',  compact('page'));
	}
    
    
    public function create()
	{
		return view('back.pages.create');
	}

	public function store(PageCreateRequest $request)
	{
        $page = new Page;
        
        $page->title = $request->get('title');
		$page->content = $request->get('description');
		$page->meta_title = $request->get('meta_title');
		$page->meta_description = $request->get('meta_description');
		$page->meta_keyword = $request->get('meta_keyword');
		$page->created_at = date('Y-m-d h:i:s');
		$page->updated_at = date('Y-m-d h:i:s');
		
        if($page->save()){
            return redirect('page')->with('ok', trans('back/page.created'));
        }
        else{
            return redirect('page')->with('ok', trans('back/page.err'));
        }
	}
   
    public function edit(Page $page) {
      return view('back.pages.edit', compact('page'));
    }
    
    public function update(Request $request,Page $page){
        if($page->id!=''){
		  $page = Page::find($page->id);
		}else{
		  $page = new Page;
		}
        
        $page->title = $request->get('title');
		$page->content = $request->get('content');
		$page->meta_title = $request->get('meta_title');
		$page->meta_description = $request->get('meta_description');
		$page->meta_keyword = $request->get('meta_keyword');
        $page->status = $request->get('status');
		$page->created_at = date('Y-m-d h:i:s');
		$page->updated_at = date('Y-m-d h:i:s');

        if($page->save()){
            return redirect('page')->with('ok', trans('back/page.updated'));
        }
        else{
            return redirect('page')->with('ok', trans('back/page.err'));
        }
    }
    
    public function destroy(Request $request, $id)
	{
        $plan = Page::find($id);	
        if($plan->delete()){
            $request->session()->flash('alert-success', 'Page was successful deleted!');
            return redirect("page")->with('ok', trans('back/page.destroyed'));;
        }
	}
    
    
    public function detail($id){
        $total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
		
        $Form_action = '';
       	$search = '';
       	if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
            $search = $_REQUEST['search'];
       	}
        
        $pageData = DB::table('pages')->where('id', '=', $id)->get();

        return view('front.page.detail',compact('pageData','plan','totalAutoresponses','totalContact_forms','totalGallery','total_bots','total_chanels','Form_action','search'));
    }
}
