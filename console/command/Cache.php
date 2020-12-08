<?php

namespace MVC\Command;

class Cache
{

    static public function help(){
        print "explaination of the command\n\n";
    }

    static public function clear(){
        $git_cache_rm = system('rm -f '.VIEW_PATH.'/cache/*', $git_cache_rm_retval);
        print $git_cache_rm_retval;
        $git_logs_rm = system('rm -f '.LOG_PATH.'/*', $git_logs_rm_retval);
        print $git_logs_rm_retval;

        print "logs && cache cleared ! \n\n";
    }

    static public function stabilize(){
        $git_cache_rm = system('rm -f '.VIEW_PATH.'/cache/*', $git_cache_rm_retval);
        print $git_cache_rm_retval;
        print "cache stabilized ! \n\n";
    }

}