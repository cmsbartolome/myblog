<?php
function readableDate($datetime){
    return date("D, d M Y h:i A", strtotime($datetime));
}
function toTxtFile($destination,$filename,$mode,$content=null){
    $response = array(
        'success' =>false,
        'data' => ''
    );
    if($mode == 'put') {
        createDir($destination);
        $path = $destination . $filename . '.txt';
        File::put($path, $content);
        $response = array(
            'success' =>true,
            'data' => $content
        );
    }
    elseif($mode == 'get'){
        $filename = $filename.'.txt';
        $path = $destination.''.$filename;
        $content = '';
        if(file_exists($path)){
            $content = File::get($path);
            $response = array(
                'success' =>true,
                'data' => $content
            );
        }else{
            $response = array(
                'success' =>false,
                'data' => $content
            );

        }
    }
    elseif($mode == 'local-get'){
        $exists = Storage::disk('public')->exists($destination.'/'.$filename);
        if($exists === true)
        {
            $content = Storage::disk('public')->get($destination.'/'.$filename);
            $response = array(
                'success' =>true,
                'data' => $content
            );
        }
    }
    elseif($mode == 'local-put'){
        $resultCreateFile = Storage::disk('public')->put($destination.'/'.$filename,$content);
        $response = array(
            'success' =>$resultCreateFile,
            'data' => $content
        );
    }
    return $response;
}
function createDir($destination){
    if(!File::isDirectory($destination)) {
        File::makeDirectory($destination, 0775, true);
    }
}
function sendEmails($data,$sourceEmail,$header,$cc=array())
{
    $file = null;
    $name = null;
    $receiver = $data['receiver'];
    $subject = $data['subject'];
    $header = $data['subject'];
    $response = array();

    if(isset($data['file'])){
        $file = $data['file'];
    }

    if(isset($data['name'])){
        $name = $data['name'];
    }

    Mail::send($data['template'],$data, function ($message)
    use ($receiver,$name,$subject,$sourceEmail,$header,$cc,$file)
    {
        $message->from('inquiry@toyotabestintownpromodeals.com');
        if($file != null){
            $message->attach($file->getRealPath(),
                [
                    'as' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                ]);
        }
        if(count($cc) > 0){
            $message->cc($cc);
        }
        if($name != null){
            $message->to($receiver, $name)->subject($subject)->from($sourceEmail);
        }else{
            $message->to($receiver)->subject($subject)->from($sourceEmail);
        }
    });
    if(count(Mail::failures()) > 0){
        $response  = array(
            'success' =>false,
            'data' => Mail::failures(),
        );
    } else {
        $response  = array(
            'success' =>true,
        );
    }
    return $response;
}
function strToTitle($text){
    return ucwords(mb_strtolower($text));
}
function isExistFile($filepath){
    $path = $filepath;
    $isExist = false;
    if(file_exists($path.'.png'))
    {
        $isExist = true;
        $path =$path.'.png';
    } else if(file_exists($path.'.jpg')) {
        $isExist = true;
        $path =$path.'.jpg';
    } else if(file_exists($path.'.jpeg')) {
        $isExist = true;
        $path =$path.'.jpeg';
    } else if(file_exists($path.'.gif')) {
        $isExist = true;
        $path =$path.'.gif';
    } else if(file_exists($path.'.doc')) {
        $isExist = true;
        $path =$path.'.doc';
    } else if(file_exists($path.'.pdf')) {
        $isExist = true;
        $path =$path.'.pdf';
    } else if(file_exists($path.'.docx')) {
        $isExist = true;
        $path =$path.'.docx';
    } else if(file_exists($path.'.xlsx')) {
        $isExist = true;
        $path =$path.'.xlsx';
    }else if(file_exists($path.'.csv')) {
        $isExist = true;
        $path =$path.'.csv';
    }else if(file_exists($path.'.xls')) {
        $isExist = true;
        $path =$path.'.xls';
    }

    return $response = array(
        'is_exist' =>$isExist,
        'path' => $path
    );

}
function isSelected($text1,$text2,$returnText = null){
    $response = 'selected';
    if($returnText != null){
        $response = $returnText;
    }
    if($text1 == $text2){
        return $response;
    }
}
function getClientIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
function imagePath($path,$baseUrl)
{
    if(file_exists($path.'.png')) {
        return asset($path.'.png');
    } else if(file_exists($path.'.jpg')) {
        return asset($path.'.jpg');
    } else if(file_exists($path.'.jpeg')) {
        return asset($path.'.jpeg');
    } else if(file_exists($path.'.gif')) {
        return asset($path.'.gif');
    } else{
        return $baseUrl;
    }
}
function filePath($path,$baseUrl)
{
    if(file_exists($path.'.doc'))
    {
        return asset($path.'.doc');
    }
    else if(file_exists($path.'.pdf'))
    {
        return asset($path.'.pdf');
    }
    else if(file_exists($path.'.docx'))
    {
        return asset($path.'.docx');
    }
    else if(file_exists($path.'.csv'))
    {
        return asset($path.'.csv');
    }
    else if(file_exists($path.'.xlsx'))
    {
        return asset($path.'.xlsx');
    }
    else if(file_exists($path.'.xls'))
    {
        return asset($path.'.xls');
    }
    else if(file_exists($path.'.ppt'))
    {
        return asset($path.'.ppt');
    }
    else{
        return $baseUrl;
    }
}
function fileStorageUpload($file,$destination,$file_name,$mode,$width,$height){
    try{
        $extension = strtolower($file->getClientOriginalExtension());
        $img = Image::make($file);
        if($mode == 'resize'){
            $img->resize($width,$height);
        }
        if(!File::isDirectory($destination)) {
            File::makeDirectory($destination, 0775, true);
        }
        $img->save($destination.$file_name.'.'.$extension);
        ImageOptimizer::optimize($destination.$file_name.'.'.$extension);
        return true;
    }
    catch(\Exception $e){
        return 'err'.$e;
    }
}
function fileUpload($file,$destination,$filename){
    try{

        $extension = strtolower($file->getClientOriginalExtension());
        if(!File::isDirectory($destination)) {
            File::makeDirectory($destination, 0775, true);
        }

        $img_extension = [ 'jpg','jpeg','png'];
        $file_extension = ['doc','pdf','docx','ppt','xls','csv','xlsx'];

        if(in_array($extension, $img_extension)){
            $img = Image::make($file);
            $img->save($destination.$filename.'.'.$extension);
            ImageOptimizer::optimize($destination.$filename.'.'.$extension);
            return true;
        }

        if(in_array($extension, $file_extension)){
            $file_name=$filename.'.'.$extension;
            $file->move($destination, $file_name);
            return true;
        }
    }
    catch(\Exception $e){
        return 'err'.$e;
    }
}
function getImages($path)
{
    $dir = $path;
    $listFiles=[];
    if (file_exists($dir)) {
        $files = \File::files($dir);
        foreach($files as $path)
        {

            $listFiles[] = pathinfo($path);
        }
    }
    return $listFiles;
}
function isMobileDev(){
    if(isset($_SERVER['HTTP_USER_AGENT']) and !empty($_SERVER['HTTP_USER_AGENT'])){
        $user_ag = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/(Mobile|Android|Tablet|GoBrowser|[0-9]x[0-9]*|uZardWeb\/|Mini|Doris\/|Skyfire\/|iPhone|Fennec\/|Maemo|Iris\/|CLDC\-|Mobi\/)/uis',$user_ag)){
            return true;
        }else{
            return false;
        };
    }else{
        return false;
    };
};
function createTextFile($destination,$filename,$content)
{
    $resultCreateFile = Storage::disk('public')->put($destination.'/'.$filename,$content);
    return $resultCreateFile;
}
function getDatetimeNow() {
    date_default_timezone_set('Asia/Manila');
    $datetime = date("Y-m-d H:i:s");
    //adding date
    // $datetime = strtotime('2016-01-04 03:31:52 + 1 hour');
    // $datetime= date('Y-m-d H:i:s', $datetime);
    return $datetime;
}
function getDateNow() {
    date_default_timezone_set('Asia/Manila');
    $datetime = date("Y-m-d");
    return $datetime;
}
function encryptor($action, $string) {
    $output = false;
    $encrypt_method = env('ENCRYPTION_METHOD');
    $secret_key = env('ENCRYPTION_KEY');
    $secret_iv = env('ENCRYPTION_IV');
    $key = hash('sha256', $secret_key);

    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
function titlecase($text)
{
    return ucwords(strtolower($text));
}
function getAllImages($path)
{
    $dir = $path;
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    $listFiles=[];
    $files = \File::files($dir);
    foreach($files as $path)
    {

        $listFiles[] = pathinfo($path);
    }

    return $listFiles;
}
function uppercase_txt($text){
    return strtoupper($text);
}
