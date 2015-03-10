<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Websites extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    public static $unguarded = true;
    protected $table = 'websites';
    
    public static function createWebsite($data) {
    	    				                  						
            $id = Websites::insertGetId([
            	'id_user'  => $data['id_user'], 
                'name'  => $data['website_name'],
                'url'  => $data['website_url'],
                'keywords'       => $data['website_kw'],
                'id_category'       => $data['website_category'],
                'description'       => $data['website_desc'],
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ]);
			
			
			//$id = 30;
			if ( !empty($data['form']['ad']) ) {				
    			$ins = array();				
	    		foreach ($data['form']['ad'] as $key => $value) {	    			
					if (!empty($value['data'])){
						if ($value['type']=='file'){
							$value['data'] = self::saveImg($key,$id);							
						}												
						if (is_array($value['data'])) $value['data'] = json_encode($value['data']);									
						$ins[] = array('id_website'=>$id,'label'=>$value['label'],'name'=>$key,'value'=>$value['data'],'type'=>$value['type']);						
					}				
				}					
				if ($ins) DB::table('websites_additional')->insert($ins);					
			}															
    	
        

        return $id;
    }

	public static function saveImg($name, $id){								
		$tmp_name = $_FILES['form']['tmp_name']['ad'][$name]['data'];		
        $filename = $originalName = $_FILES['form']['name']['ad'][$name]['data'];        		
		$mime = $_FILES['form']['type']['ad'][$name]['data'];
		if (strstr($mime, 'image/'))
		{
			$path = $_SERVER['DOCUMENT_ROOT'].'/public/files/'.$id;
			$pathSmall = $_SERVER['DOCUMENT_ROOT'].'/public/files/'.$id.'/small';
			$pathPrev = $_SERVER['DOCUMENT_ROOT'].'/public/files/'.$id.'/prev';
			if (!is_dir($path)) mkdir($path, 0755);
			$filename = $path.'/'.$filename;
		    move_uploaded_file($tmp_name, $filename);
			chmod($filename, 0755);			
			
			if (!is_dir($pathSmall)) mkdir($pathSmall, 0755);
			$img = Image::make($filename);			
			$img->resize(230, null, function ($constraint) {
			    $constraint->aspectRatio();
			});			
			$img->save($pathSmall.'/'.$originalName);
			chmod($pathSmall.'/'.$originalName, 0755);
			
			if (!is_dir($pathPrev)) mkdir($pathPrev, 0755);
			$img = Image::make($filename);			
			$img->resize(800, null, function ($constraint) {
			    $constraint->aspectRatio();
			});			
			$img->save($pathPrev.'/'.$originalName);
			chmod($pathPrev.'/'.$originalName, 0755);
		}
		return $originalName;
		exit;
	}

	public static function getWebsiteById($id) {		
		$site = DB::table('websites')
		->leftJoin('categories', 'categories.id', '=', 'websites.id_category')
		->select('websites.*', 'categories.name as catname')
		->where('websites.id', $id)->first();				
		
		if (!$site)
			App::abort(404, 'Page not found');
		
		$number =  DB::table('websites_rating')->where('id_website', $id)->where('confirm_hash', '')->where('type','<>','input_text')->where('type','<>','select_data')->count();
		$sum =DB::table('websites_rating')->where('id_website', $id)->where('confirm_hash', '')->where('type','<>','input_text')->where('type','<>','select_data')->sum('value');						
		$site->rating =  ($number>0) ? round($sum / $number,1) : 0;
		DB::table('websites')->where('id', $id)->update(array('rating' => $site->rating));
		return $site; 
	}

	public static function getWebsiteRatingById($id) {
		$number =  DB::table('websites_rating')->where('id_website', $id)->where('confirm_hash', '')->where('type','<>','input_text')->where('type','<>','select_data')->count();
		$sum =DB::table('websites_rating')->where('id_website', $id)->where('confirm_hash', '')->where('type','<>','input_text')->where('type','<>','select_data')->sum('value');						
		$rating =  ($number>0) ? round($sum / $number,1) : 0; 
		return $rating;
	}

	public static function getWebsiteAdditionalById($id){
		$list =  DB::table('websites_additional')->where('id_website', $id)->where('type','<>', 'file')->get();
		return $list;
		
	}

	public static function getWebsiteImgId($id){
		$data['prev'] = '';
		$data['small'] = '';
		$img =  DB::table('websites_additional')->where('id_website', $id)->where('type', 'file')->first();
		if($img){
			if($img->value){
				$fileSmall = '/public/files/'.$id.'/small/'.$img->value;
				$pathToImg = $_SERVER['DOCUMENT_ROOT'].$fileSmall;
				if (file_exists($pathToImg)){
					$data['small'] = $fileSmall;
				}
				$fileSmall = '/public/files/'.$id.'/prev/'.$img->value;
				$pathToImg = $_SERVER['DOCUMENT_ROOT'].$fileSmall;
				if (file_exists($pathToImg)){
					$data['prev'] = $fileSmall;
				}
			}
		}		
		return $data;
		exit;
	}

	public static function ratingsave($data){				
				
		$session_id = Session::getId();		
		$hash = md5(uniqid());
		
		if ($data['id_user']>0) 
			$hash = '';
		else{			
			$data['hash'] = $hash; 
			Mail::send('emails.confirmrating', $data, function ($message) use ($data) {                
				$message->from(Config::get('app.noreplyemail'), Lang::get('validation.emailfrom'));
                $message->to($data['email']);
                $message->subject(Lang::get('validation.subjectrating'));
    		});
		}
		
		$ins = array();
		
		foreach ($data['form'] as $key => $value) {	    			
			if (!empty($value['data'])){																							
				if (isset($value['type']) and $value['type'] == 'select_data'){
					$value['data'] = implode('/', $value['data']);
				}								
				$ins[] = array('confirm_hash'=>$hash,'id_user'=>$data['id_user'],'id_website'=>$data['id_website'],'label'=>$value['label'],'name'=>$key,'value'=>$value['data'],'type'=>$value['type'],'id_question'=>$value['id_question']);										
			}				
		}
		
	    if ($ins) DB::table('websites_rating')->insert($ins);			
		
	}
	
	public static function getRaiting($id,$data){				
		$number =  DB::table('websites_rating')->where('id_question', $data->id)->where('id_website', $id)->where('confirm_hash', '')->where('type','<>','input_text')->where('type','<>','select_data')->count();
		$sum =DB::table('websites_rating')->where('id_question', $data->id)->where('id_website', $id)->where('confirm_hash', '')->where('type','<>','input_text')->where('type','<>','select_data')->sum('value');								
		$rating =  ($number>0) ? round($sum / $number,1) : 0; 		
		return $rating;		
	}		

    public static function sendcomment($data){    	
    	$id = DB::table('comments')->insertGetId($data);
		return $id;
	}
	
    public static function getAnswer($id_parent){
    	return DB::table('comments')
		->select('comments.id','comments.comm','comments.date','comments.id_parent','comments.id_user','comments.id_site', 'users.photo', 'users.firstname','users.lastname')
		->leftJoin('users', 'users.id', '=', 'comments.id_user')
    	->where('comments.id_parent',$id_parent)
		->orderby('comments.date','ASC')
    	->get();		
    }
    
    /*
     * Change status for website: available / unavailable 
     */
    public static function changeStatusWebsite($id) {
        if($id) {
            $currentdata = Websites::find((int) $id);
            Websites::find((int) $id)->update([
                'active'    => $currentdata->active ? 0 : 1
            ]);
        }
    }
    
 
    /*
     * Save web site info from admin panel
     */
    public static function saveWebsite($data, $id) {
        Websites::find((int) $id)->update([
            'name'          => isset($data['name'])         ? $data['name']         : '',
            'url'           => isset($data['url'])          ? $data['url']          : '',
            'id_category'   => isset($data['id_category'])  ? $data['id_category']  : '',
            'keywords'      => isset($data['keywords'])     ? $data['keywords']     : '',
            'description'   => isset($data['description'])  ? $data['description']  : ''
        ]); 
    }
    
    /*
     * Save additional web site info from admin panel
     */
    public static function saveAddWebsite($data, $id) { //dd($data);
        $addfields = Websitefield::all();
        $ins = array();	
        
        foreach ($addfields as $field) {
            $key_has = 0;
            
            foreach ($data as $key => $value) {
                
                if(is_array($data[$key])) {
                    $value = '';
                    foreach ($data[$key] as $selectedOption) {
                        $value .= $selectedOption.';';
                    }
                }
                
                if($key == $field->name) {
                    $key_has = 1;
                    
                    $currentdata =  \DB::table('websites_additional')->where('name', $key)->where('id_website', $id)->get();
                    
                    if($field->type == 'file') {
                        $tmp_name = $_FILES[$key]['tmp_name'];		
                        $filename = $originalName = $_FILES[$key]['name'];       		
                        $mime = $_FILES[$key]['type'];
                        if (strstr($mime, 'image/')) {
                            $path = $_SERVER['DOCUMENT_ROOT'].'/public/files/'.$id;
                            $pathSmall = $_SERVER['DOCUMENT_ROOT'].'/public/files/'.$id.'/small';
                            $pathPrev = $_SERVER['DOCUMENT_ROOT'].'/public/files/'.$id.'/prev';
                            if (!is_dir($path)) {
                                mkdir($path, 0755, true);
                            }
                            $filename = $path.'/'.$filename;
                            move_uploaded_file($tmp_name, $filename);
                            chmod($filename, 0755);			

                            if (!is_dir($pathSmall)) { mkdir($pathSmall, 0755, true); }
                            $img = Image::make($filename);			
                            $img->resize(230, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });			
                            $img->save($pathSmall.'/'.$originalName);
                            chmod($pathSmall.'/'.$originalName, 0755);

                            if (!is_dir($pathPrev)) { mkdir($pathPrev, 0755, true); }
                            $img = Image::make($filename);			
                            $img->resize(800, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });			
                            $img->save($pathPrev.'/'.$originalName);
                            chmod($pathPrev.'/'.$originalName, 0755);
                            $value = $originalName;
                        } else {
                            $value = $currentdata[0]->value;
                        }
                    } 

                    if($currentdata) {
                        $upd = array(
                            'id_website'    =>  $id,
                            'label'         =>  $field->label,
                            'name'          =>  $key,
                            'value'         =>  $value,
                            'type'          =>  $field->type
                        );
                        DB::table('websites_additional')->where('name', $key)->where('id_website', $id)->update($upd);
                    } else {
                        $ins[] = array(
                            'id_website'    =>  $id,
                            'label'         =>  $field->label,
                            'name'          =>  $key,
                            'value'         =>  $value,
                            'type'          =>  $field->type
                        );										
                    } 
                }
            }
            if(!$key_has && $field->type != 'file') {
                $currentdata =  \DB::table('websites_additional')->where('name', $field->name)->where('id_website', $id)->get();

                if($currentdata) {
                    $upd = array(
                        'id_website'    =>  $id,
                        'label'         =>  $field->label,
                        'name'          =>  $key,
                        'value'         =>  '',
                        'type'          =>  $field->type
                    );
                    DB::table('websites_additional')->where('name', $field->name)->where('id_website', $id)->update($upd);
                }
            }
        }
        
        if($ins) {
            DB::table('websites_additional')->insert($ins);
        }
    }
    
}