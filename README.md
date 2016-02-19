# codeigniter-ldap
codeigniter ldap class<br/>

for using this class , just put in /application/libraries<br/>
<br/>
load in config/autoload.php<br/>
<br/>

$autoload['libraries'] = array('ldap');<br/>
<br/>
setting configure ldap 
<br/><br/>
$config['ldap_server'] = 'localhost'; <br/>
$config['ldap_port'] = '389';<br/>
$config['ldap_bs'] = 'dc=test,dc=com,dc=my';<br/>
$config['ldap_user'] = 'ou=users,dc=test,dc=com,dc=my';<br/>
$config['ldap_admin'] = 'cn=admin,dc=test,dc=com,dc=my';<br/>
$config['ldap_password'] = 'password';<br/><br/>

you nead install openldap and set the ldap admin user and password first.<br/>
<br/>
now you can call the class from controllers or models<br/>
<br/>
just <br/>

$this->ldap->test();<br/>
<br/>
eg:- for add user<br/>
<br/>
$info["givenName"]="tets";<br/>
$info["sn"]="tets";<br/>
$info["uid"]="tets";<br/>
$info["mail"]="tets";<br/>
$info["displayName"]= "tets";<br/>
$info["gidNumber"] = 500;<br/>
$info["uidNumber"] = 500;<br/>
$info["homeDirectory"] = "/home/hasnan";<br/>
$info["cn"] = $user;<br/>
$info["userPassword"]="tets{sha}";<br/>
$info["objectclass"][0] = "top";<br/>
$info["objectclass"][1] = "person";<br/>
$info["objectclass"][2] = "inetOrgPerson";<br/>
$info["objectclass"][3] = "organizationalPerson";<br/>
$info["objectclass"][4] = "posixAccount";<br/><br/>


$this->ldap->add_user($user,$info);

