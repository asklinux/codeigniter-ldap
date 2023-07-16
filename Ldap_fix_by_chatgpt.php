<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ldap {

    private $ldapconn;

    public function __construct()
    {
        $ci =& get_instance();
        $this->ldapconn = ldap_connect($ci->config->item('ldap_server'), $ci->config->item('ldap_port'))
            or die("Could not connect to LDAP server");
        ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldapconn, LDAP_OPT_REFERRALS, 0);
    }

    public function __destruct()
    {
        ldap_close($this->ldapconn);
    }

    private function bind()
    {
        $ci =& get_instance();
        $ldapbind = @ldap_bind($this->ldapconn, $ci->config->item('ldap_admin'), $ci->config->item('ldap_password'));
        return $ldapbind;
    }

    public function test()
    {
        $e = $this->ldapconn;
        echo $e;
    }

    private function getBaseDN($user)
    {
        $ci =& get_instance();
        return 'cn='.$user.','.$ci->config->item('ldap_user');
    }

    public function add_user($user = NULL, $data = NULL)
    {
        $e = $this->ldapconn;
        $bs_u = $this->getBaseDN($user);
        $p = $this->bind();
        $p = ldap_add($e, $bs_u, $data);
        if ($p == 1) {
            return "success";
        } else {
            return "error";
        }
    }

    public function add_user_info($user = NULL, $data = NULL)
    {
        $e = $this->ldapconn;
        $bs_u = $this->getBaseDN($user);
        $p = $this->bind();
        $p = ldap_mod_add($e, $bs_u, $data);
        if ($p == 1) {
            return "success";
        } else {
            return "error";
        }
    }

    public function edit_user($user = NULL, $data = NULL)
    {
        $e = $this->ldapconn;
        $bs_u = $this->getBaseDN($user);
        $p = $this->bind();
        $p = ldap_modify($e, $bs_u, $data);
        if ($p == 1) {
            return "success";
        } else {
            return "error";
        }
    }

    public function delete_user($user = NULL)
    {
        $e = $this->ldapconn;
        $bs_u = $this->getBaseDN($user);
        $p = $this->bind();
        $p = ldap_delete($e, $bs_u);
        if ($p == 1) {
            return "success";
        } else {
            return "error";
        }
    }

    public function list_user()
    {
        $e = $this->ldapconn;
        $ci =& get_instance();
        $justthese = array("ou");
        $p = $this->bind();
        $filter = "(|(sn=*))";
        $justthese = array("ou", "sn", "givenname", "mail");
        $sr = ldap_search($e, $ci->config->item('ldap_user'), $filter, $justthese);
        $getlist = ldap_get_entries($e, $sr);
        return $getlist;
    }

    public function check_username($user = NULL)
    {
        $e = $this->ldapconn;
        $ci =& get_instance();
        $justthese = array("ou");
        $p = $this->bind();
        $du = $this->getBaseDN($user);
        $filter = "(|(cn=$user))";
        $justthese = array("ou", "sn", "givenname", "mail", "employeetype");
        try {
            $sr = ldap_search($e, $du, $filter, $justthese);
            return ldap_get_entries($e, $sr);
        } catch (Exception $e) {
            return 0;
        }
    }

    public function get_bahagian($user = NULL)
    {
        $e = $this->ldapconn;
        $ci =& get_instance();
        $justthese = array("ou");
        $p = $this->bind();
        $du = $this->getBaseDN($user);
        $filter = "(|(cn=$user))";
        $justthese = array("gidNumber");
        $sr = ldap_search($e, $du, $filter, $justthese);
        if ($sr === FALSE) {
            return 0;
        } else {
            $getgroup = ldap_get_entries($e, $sr);
            $gid = $getgroup[0]['gidnumber'][0];
            $gn = "dc=dosh,dc=gov,dc=my";
            $fgn = "(|(gidNumber=$gid))";
            $jget = array("cn");
            $sgn = ldap_search($e, $gn, $fgn, $jget);
            $gname = ldap_get_entries($e, $sgn);
            return $gname[0]['cn'][0];
        }
    }

    public function check_login($user = NULL, $password = NULL)
    {
        $e = $this->ldapconn;
        $ci =& get_instance();
        $justthese = array("ou");
        $p = $this->bind();
        if ($this->check_username($user) !== 0) {
            $du = $this->getBaseDN($user);
            $attr = "userPassword";
            $encoded_newPassword = "{SHA}" . base64_encode(pack("H*", sha1($password)));
            $r = ldap_compare($e, $du, $attr, $encoded_newPassword);
            if ($r === -1) {
                return "Error";
            } elseif ($r === true) {
                $datauser = $this->check_username($user);
                $session_data = array(
                    'username' => $user,
                    'email' => $datauser[0]['mail'][0],
                    'jenis' => $datauser[0]['employeetype'][0],
                );
                $ci->session->set_userdata('logged_in', $session_data);
                return "Successfully Logged in...";
            } elseif ($r === false) {
                return "Email or Password is wrong...!!!!";
            } else {
                echo "apa ko buat";
            }
        } else {
            echo "no user";
        }
    }

}
