<?php

namespace MVC\Command;

class Cache
{
    public static function help()
    {
        print "Cette commande permet de vider le cache du framework\n\n";
        print "Elle peut accepter les attributs suivants\n";
        print "\t- clear : pour vider les logs et le cache des vues\n";
        print "\t- stabilize : pour vider uniquement le cache des vues\n";
    }

    public static function clear()
    {
        $git_cache_rm = system('rm -f '.VIEW_PATH.'/cache/*', $git_cache_rm_retval);
        print $git_cache_rm_retval;
        $git_logs_rm = system('rm -f '.LOG_PATH.'/*', $git_logs_rm_retval);
        print $git_logs_rm_retval;

        print "logs && cache cleared ! \n\n";
    }

    public static function stabilize()
    {
        $git_cache_rm = system('rm -f '.VIEW_PATH.'/cache/*', $git_cache_rm_retval);
        print $git_cache_rm_retval;
        print "cache stabilized ! \n\n";
    }
}
