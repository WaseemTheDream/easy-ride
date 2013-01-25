<?php
// basic sequence with LDAP is connect, bind, search, interpret search
// result, close connection

echo "<h3>LDAP query test</h3>";
echo "Connecting ...";
$ds=ldap_connect("ldaps://ldap.rice.edu");  // must be a valid LDAP server!
echo "connect result is " . $ds . "<br />";

if ($ds) { 
    echo "Binding ..."; 
    $r=ldap_bind($ds);     // this is an "anonymous" bind, typically
                           // read-only access
    echo "Bind result is " . $r . "<br />";

    $sr=ldap_search($ds, "OU=People, DC=rice, DC=edu", "(&(riceuserstatus=active)(ricecampuslocation=*Duncan*)(sn=r*))");  
    echo "Search result is " . $sr . "<br />";

    echo "Number of entires returned is " . ldap_count_entries($ds, $sr) . "<br />";

    echo "Getting entries ...<p>";
    $info = ldap_get_entries($ds, $sr);
    echo "Data for " . $info["count"] . " items returned:<p>";

    for ($i=0; $i<$info["count"]; $i++) {
        echo "dn is: " . $info[$i]["dn"] . "<br />";
        echo "first cn entry is: " . $info[$i]["cn"][0] . "<br />";
        echo "first email entry is: " . $info[$i]["mail"][0] . "<br /><hr />";
        
	echo json_encode($info[$i]);
        echo "<hr />";
    }

    echo "Closing connection";


echo $attrs["count"] . " attributes held for this entry:<p>";

for ($i=0; $i < $attrs["count"]; $i++) {
    echo $attrs[$i] . "<br />";
}

    ldap_close($ds);

} else {
    echo "<h4>Unable to connect to LDAP server</h4>";
}
?>