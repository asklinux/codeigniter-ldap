# codeigniter-ldap
codeigniter ldap class

for using this class , just put in /application/libraries

load in config/autoload.php


$autoload['libraries'] = array('ldap');

setting configure ldap 

$config['ldap_server'] = 'localhost';
$config['ldap_port'] = '389';
$config['ldap_bs'] = 'dc=test,dc=com,dc=my';
$config['ldap_user'] = 'ou=users,dc=test,dc=com,dc=my';
$config['ldap_admin'] = 'cn=admin,dc=test,dc=com,dc=my';
$config['ldap_password'] = 'password';

you nead install openldap and set the ldap admin user and password first.

now you can call the class from controllers or models

just 

$this->ldap->test();

eg:- for add user

$info["givenName"]="tets";
$info["sn"]="tets";
$info["uid"]="tets";
$info["mail"]="tets";
$info["displayName"]= "tets";
$info["gidNumber"] = 500;
$info["uidNumber"] = 500;
$info["homeDirectory"] = "/home/hasnan";
$info["cn"] = $user;
$info["userPassword"]="tets{sha}";
$info["objectclass"][0] = "top";
$info["objectclass"][1] = "person";
$info["objectclass"][2] = "inetOrgPerson";
$info["objectclass"][3] = "organizationalPerson";
$info["objectclass"][4] = "posixAccount";


$this->ldap->add_user($user,$info);

