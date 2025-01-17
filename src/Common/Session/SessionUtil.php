<?php
/**
 * start/destroy session/cookie for OpenEMR or OpenEMR (patient) portal
 *
 * OpenEMR session/cookie strategy:
 *  1. The vital difference between the OpenEMR and OpenEMR (patient) portal session/cookie is the
 *     cookie_httponly setting.
 *     a. For core OpenEMR, need to set cookie_httponly to false, since javascript needs to be able to
 *        access/modify the cookie to support separate logins in OpenEMR. This is important
 *        to support in OpenEMR since the application needs to robustly support access of
 *        separate patients via separate logins by same users. This is done via custom
 *        restore_session() javascript function; session IDs are effectively saved in the
 *        top level browser window.
 *     b. For (patient) portal OpenEMR, setting cookie_httponly to true. Since only one patient will
 *        be logging into the patient portal, can set this to true, which will help to prevent XSS
 *        vulnerabilities.
 *  2. If using php version 7.3.0 or above, then will set the cookie_samesite to Strict in
 *     order to prevent csrf vulnerabilities.
 *  3. Using use_strict_mode, use_cookies, and use_only_cookies to optimize security.
 *  4. Using sid_bits_per_character of 6 to optimize security. This does allow comma to
 *     be used in the session id, so need to ensure properly escape it when modify it in
 *     cookie.
 *  5. Using sid_length of 48 to optimize security.
 *  6. Setting gc_maxlifetime to 14400 since defaults for session.gc_maxlifetime is
 *     often too small.
 *  7. For core OpenEMR, setting cookie_path to improve security when using different OpenEMR instances
 *     on same server to prevent session conflicts.
 *  8. Centralize session/cookie destroy.
 *     a.  For core OpenEMR, destroy the session, but keep the cookie.
 *     b.  For api OpenEMR and (patient) portal OpenEMR, destroy the session and cookie.
 *
 * @package   OpenEMR
 * @link      https://www.open-emr.org
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2019 Brady Miller <brady.g.miller@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */


namespace OpenEMR\Common\Session;

class SessionUtil
{
    private static $gc_maxlifetime = 14400;
    private static $sid_bits_per_character = 6;
    private static $sid_length = 48;
    private static $use_strict_mode = true;
    private static $use_cookies = true;
    private static $use_only_cookies = true;

    public static function coreSessionStart($web_root)
    {
        if (version_compare(phpversion(), '7.3.0', '>=')) {
            session_start([
                'cookie_samesite' => "Strict",
                'name'=> 'OpenEMR',
                'cookie_httponly' => false,
                'cookie_path' => $web_root ? $web_root : '/',
                'gc_maxlifetime' => self::$gc_maxlifetime,
                'sid_bits_per_character' => self::$sid_bits_per_character,
                'sid_length' => self::$sid_length,
                'use_strict_mode' => self::$use_strict_mode,
                'use_cookies' => self::$use_cookies,
                'use_only_cookies' => self::$use_only_cookies
            ]);
        } else {
            session_start([
                'name' => 'OpenEMR',
                'cookie_httponly' => false,
                'cookie_path' => $web_root ? $web_root : '/',
                'gc_maxlifetime' => self::$gc_maxlifetime,
                'sid_bits_per_character' => self::$sid_bits_per_character,
                'sid_length' => self::$sid_length,
                'use_strict_mode' => self::$use_strict_mode,
                'use_cookies' => self::$use_cookies,
                'use_only_cookies' => self::$use_only_cookies
            ]);
        }
    }

    public static function coreSessionDestroy()
    {
        session_destroy();
    }

    public static function apiSessionCookieDestroy()
    {
        self::portalSessionCookieDestroy();
    }

    public static function portalSessionStart()
    {
        if (version_compare(phpversion(), '7.3.0', '>=')) {
            session_start([
                'cookie_samesite' => "Strict",
                'name'=> 'PortalOpenEMR',
                'cookie_httponly' => true,
                'gc_maxlifetime' => self::$gc_maxlifetime,
                'sid_bits_per_character' => self::$sid_bits_per_character,
                'sid_length' => self::$sid_length,
                'use_strict_mode' => self::$use_strict_mode,
                'use_cookies' => self::$use_cookies,
                'use_only_cookies' => self::$use_only_cookies
            ]);
        } else {
            session_start([
                'name' => 'PortalOpenEMR',
                'cookie_httponly' => true,
                'gc_maxlifetime' => self::$gc_maxlifetime,
                'sid_bits_per_character' => self::$sid_bits_per_character,
                'sid_length' => self::$sid_length,
                'use_strict_mode' => self::$use_strict_mode,
                'use_cookies' => self::$use_cookies,
                'use_only_cookies' => self::$use_only_cookies
            ]);
        }
    }

    public static function portalSessionCookieDestroy()
    {
        // Destroy the cookie
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );

        // Destroy the session.
        session_destroy();
    }
}
