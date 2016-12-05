<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Contracts\Auth\User;
use Auth;
use App\Http\Controllers\Auth\AuthController;

use App\Models\Emailtemplate;

class EmailtemplateController extends Controller
{
	public function __construct() {
		parent::login_check();
    }
	
    public function index(){
        $templates = Emailtemplate::latest()->paginate(20);
		$links = $templates->render();
		return view('back.emailtemplates.index', compact('templates','links'));	
    }
    
    public function show(Emailtemplate $emailtemplate)
	{
        $template = $emailtemplate;
        return view('back.emailtemplates.show',  compact('template'));
	}
    
    
    public function create()
	{
		return view('back.emailtemplates.create');
	}

	public function store(Request $request)
	{
        $this->validate($request, [
            'title' => 'required',
			'subject' => 'required',
            'description' => 'required',
			'from' => 'required',
			'reply_to_email' => 'required',
			'is_html' => 'required',
            'status' => 'required'
        ]);
       
        $email_template = new Emailtemplate;     
        $email_template->title = $request->get('title');
		$email_template->subject = $request->get('subject');
		$email_template->description = $request->get('description');
		$email_template->from = $request->get('from');
		$email_template->reply_to_email = $request->get('reply_to_email');
		$email_template->is_html = $request->get('is_html');
		$email_template->status = $request->get('status');
		$email_template->created_at = date('Y-m-d h:i:s');
		$email_template->updated_at = date('Y-m-d h:i:s');
        
        if($email_template->save()){
            return redirect('emailtemplate')->with('ok', trans('back/emailtemplate.created'));
        }
        else{
            return redirect('emailtemplate')->with('ok', trans('back/emailtemplate.err'));
        }
	}
   
    public function edit(Emailtemplate $emailtemplate) {
      return view('back.emailtemplates.edit', compact('emailtemplate'));
    }
    
    public function update(Request $request,Emailtemplate $emailtemplate){
        $this->validate($request, [
            'title' => 'required',
			'subject' => 'required',
            'description' => 'required',
			'from' => 'required',
			'reply_to_email' => 'required',
			'is_html' => 'required',
            'status' => 'required'
        ]);
        
        if($emailtemplate->id!=''){
		  $emailtemplate = Emailtemplate::find($emailtemplate->id);
		}else{
		  $emailtemplate = new Emailtemplate;
		}
        
        $emailtemplate->title = $request->get('title');
		$emailtemplate->subject = $request->get('subject');
		$emailtemplate->description = $request->get('description');
		$emailtemplate->from = $request->get('from');
		$emailtemplate->reply_to_email = $request->get('reply_to_email');
		$emailtemplate->is_html = $request->get('is_html');
		$emailtemplate->status = $request->get('status');
		$emailtemplate->updated_at = date('Y-m-d h:i:s');

        if($emailtemplate->save()){
            return redirect('emailtemplate')->with('ok', trans('back/emailtemplate.updated'));
        }
        else{
            return redirect('emailtemplate')->with('ok', trans('back/emailtemplate.err'));
        }
    }
    
    public function destroy(Request $request, $id)
	{
        $email_template = Emailtemplate::find($id);	
        if($email_template->delete()){
            $request->session()->flash('alert-success', 'Emailtemplate was successful deleted!');
            return redirect("emailtemplate")->with('ok', trans('back/emailtemplate.destroyed'));;
        }
	}
}
