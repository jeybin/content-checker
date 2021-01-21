<?php

namespace Jeybin\ContentChecker;
class ContentChecker
{


    public static function commentExplicitChecker($comment,$required_language='english'){
        mb_regex_encoding('UTF-8');
        if($required_language == 'arabic' && !mb_ereg('[\x{0600}-\x{06FF}]', $comment)){ 
            return self::resp(422,'Invalid request, the content is not arabic'); 
        }
        if($required_language == 'hebrew' && !mb_ereg('[\x{0590}-\x{05FF}]', $comment)){ 
            return self::resp(422,'Invalid request, the content is not hebrew'); 
        }

        $explicit_words_list      = include(__DIR__.'/assets/explicitLanguage.php');
        $explicit_words_list      = $explicit_words_list[$required_language];
        $explicitWordChecker      = new ExplicitLanguageChecker($explicit_words_list);
        $checkComment             = $explicitWordChecker->hasProfanity($comment);
        return ($checkComment) ? self::resp(417,'Explicit content')
                               : self::resp(200,'Clean content');
    }


    public static function resp($code,$message){
        $error = ($code !== 200) ? true : false;
        return response()->json(['code'=>$code,'message'=>$message,'error'=>$error]);
    }

}