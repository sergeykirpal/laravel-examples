<?php

class WebsiteController extends BaseController {

	/*
	 * Add new Website
	 */
	public function addnew() {
		$data['meta_title'] = 'Add new Website';
		if(Auth::check()){
			return Redirect::to('website/add');			
		}
		return View::make('website.addnew',$data);
	}

	/*
	 * Add new Website Form
	 */
	public function add() {				
		$data['meta_title'] = 'Add new Website';
		$data['categories'] = Categorie::all();	
		$data['fields'] = Websitefield::all();	
		return View::make('website.add', $data);
	}
	
	/*
	 * Save Website Form
	 */
	public function save() {
		$data = Input::all();
		$data['id_user'] = intval(Auth::id());
        $id = Websites::createWebsite($data);
		if ($id){
			return Redirect::to('website/view/'.$id);	
		}
					
	}

	public function success() {
		return View::make('website.mass.success');
	}
	/*
	 * All Websites
	 */
	public function index() {
		$data['meta_title'] = 'Websites Lists';
		$data['meta_description'] = 'Websites Lists';
		$data['title'] ='';		
		$per_page = 16;		
		 $query = DB::table('websites')		 
		 ->orderby('rating','DESC')
		 ->orderby('created_at','DESC');
		 
		$search = Input::get('search');
		if ($search){
			$query->where('name','LIKE','%'.$search.'%')->orwhere('url','LIKE','%'.$search.'%');
		}

		$data['paginator'] = $query->paginate($per_page);							
		return View::make('website.websites',$data);
	}

	/*
	 * best Websites
	 */
	public function best() {
		$data['meta_title'] = 'Best Websites';
		$data['meta_description'] = 'Best Websites';		
		$data['title'] = 'Best Websites';
		
		$sort = Websitefilter::all();				
		if (isset($sort[0])){	
		$per_page = $sort[0]->best_show;		
		 $data['paginator'] = DB::table('websites')		 
		 ->orderby('rating','DESC')
		 ->orderby('created_at','DESC')
		 ->whereBetween('rating', array($sort[0]->best_min, $sort[0]->best_max))
		 ->paginate($per_page);	
		}
		return View::make('website.websites', $data);
	}

	/*
	 * moderate Website
	 */
	public function moderate() {
		$data['meta_title'] = 'Moderate Websites';
		$data['meta_description'] = 'Moderate Websites';
		$data['title'] = 'Moderate Websites';
		
		$sort = Websitefilter::all();		
		if (isset($sort[0])){											
		$per_page = $sort[0]->moderate_show;	
			
		 $data['paginator'] = DB::table('websites')		 
		 ->orderby('rating','DESC')
		 ->orderby('created_at','DESC')
		 ->whereBetween('rating', array($sort[0]->moderate_min, $sort[0]->moderate_max))
		 ->paginate($per_page);	
		}
		return View::make('website.websites', $data);
	}

	/*
	 * worst Website
	 */
	public function worst() {
		$data['meta_title'] = 'Worst Websites';
		$data['meta_description'] = 'Worst Websites';
		$data['title'] = 'Worst Websites';
		$sort = Websitefilter::all();				
		if (isset($sort[0])){	
		$per_page = $sort[0]->worst_show;		
		 $data['paginator'] = DB::table('websites')		 
		 ->orderby('rating','DESC')
		 ->orderby('created_at','DESC')
		 ->whereBetween('rating', array($sort[0]->worst_min, $sort[0]->worst_max))
		 ->paginate($per_page);	
		}
		return View::make('website.websites', $data);
	}

	/*
	 * Website Rating
	 */
	public function view($id) {
		$data['website'] = Websites::getWebsiteById($id);	
		$data['websiteAdditional'] = Websites::getWebsiteAdditionalById($id);
		$data['websiteImg'] = Websites::getWebsiteImgId($data['website']->id);
		$data['meta_title'] = Lang::get('title.WebsiteRatingView');	
		$data['questions'] = Question::all();
		foreach ($data['questions'] as $key => $value) {			
			$data['questions'][$key]->raiting = Websites::getRaiting($id,$value);
		}
		$data['user'] = Auth::user();
		
		$per_page = 8;		
		$data['paginator'] = DB::table('comments')
		->leftJoin('users', 'users.id', '=', 'comments.id_user')
		->where('comments.id_site',$id)
		->where('comments.id_parent','0')
		->orderby('comments.date','DESC')
		->select('comments.id','comments.comm','comments.date','comments.id_parent','comments.id_user','comments.id_site', 'users.photo', 'users.firstname','users.lastname')
		->paginate($per_page);	
		foreach ($data['paginator'] as $key => $comment){
			$data['paginator'][$key]->answers = Websites::getAnswer($comment->id);
		}			
		
		return View::make('website.view',$data);
	}
	
	public function rating($id) {
		$data['website'] = Websites::getWebsiteById($id);
		$data['websiteAdditional'] = Websites::getWebsiteAdditionalById($id);
		$data['websiteImg'] = Websites::getWebsiteImgId($data['website']->id);
		$data['meta_title'] = Lang::get('title.website_rating');
		$data['questions'] = Question::all();				
        $data['answers'] = Answer::all();
		$data['id'] = $id;			
		return View::make('website.rating',$data);
	}

	public function ratingverify($hash) {		
		$id_website = DB::select('select DISTINCT id_website from websites_rating where confirm_hash = ?', array($hash));
		if (!$id_website) 
			App::abort(404, 'Page not found');		
		$data['website'] = Websites::getWebsiteById($id_website[0]->id_website);
		$data['meta_title'] = Lang::get('title.website_rating');
		
		DB::table('websites_rating')->where('confirm_hash', $hash)->update(array('confirm_hash' => ''));
		
		return View::make('website.ratingverify',$data);		
	}
	
	public function ratingsave($id) {
		$mess = '?vote=ok';
		$data = Input::all();
		$data['id_website'] = $id;
		$data['id_user'] = intval(Auth::id());
		
		$check =  DB::table('websites_rating')->where('id_website', $id)->where('id_user','<>',0)->where('id_user', $data['id_user'])->where('confirm_hash', '')->first();
		if ($check){
			return Redirect::to('website/rating/'.$id.'?vote=done');	
		}else {
			Websites::ratingsave($data);
			if (isset($data['email']) and $data['email']){
				$mess = '?vote=confirm';
			}
			return Redirect::to('website/view/'.$id.$mess);			
		}
		
        
	}
	
	public function check(){
		$data = Input::all();
		$website = DB::table('websites')->where('url','=', $data['website_url'])->first();
		if($website){
			echo 'false';	
		}else{
			echo 'true';
		}		
	}
	
	public function sendcomment(){
		$data = Input::all();
		$data['id_user'] = Auth::user()->id;
		$data['date'] = date('Y-m-d H:i:s');		
		$data['id'] = Websites::sendcomment($data);
		
		$data['date_f'] = date('Y/m/d, H:i');		
		$data['user'] = Auth::user();	
		$data['photo'] = $data['user']->photo;
    	$data['firstname'] = $data['user']->firstname;
    	$data['lastname'] = $data['user']->lastname;	
		if ($data['id']) {
			return View::make('website.comment.comment',$data);
		}
	}
	
	public function sendcommentanswer(){
		$data = Input::all();
		$data['id_user'] = Auth::user()->id;
		$data['date'] = date('Y-m-d H:i:s');		
		$data['id_a'] = Websites::sendcomment($data);
		
		$data['date_f_a'] = date('Y/m/d, H:i');		
		$data['user'] = Auth::user();	
		$data['id_user_a'] = $data['id_user'];
		$data['comm_a'] = $data['comm'];			
		$data['photo_a'] = $data['user']->photo;
    	$data['firstname_a'] = $data['user']->firstname;
    	$data['lastname_a'] = $data['user']->lastname;
		if ($data['id_a']) {
			return View::make('website.comment.comment_answer',$data);
		}
	}
	public function removecomment(){
		$data = Input::all();
		DB::table('comments')->where('id', $data['id'])->orwhere('id_parent', $data['id'])->delete();
		echo TRUE;
	}
	
	public function iframe($id){
		$data = Input::all();
		$data['website'] = Websites::getWebsiteById($id);
		return View::make('website.iframe.iframe',$data);
	}

}
