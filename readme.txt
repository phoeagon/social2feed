Social2Feed


author: phoeagon
blog : http://blog.qooza.hk/phoeagon

Introduction:
This project tends to generate feeds for a given account on some famous and widely-used Web2.0 sites. As it's often the case, lots of netizens have accounts on various of social networks and are "following" others on them too. Unfortunately lots of Chinese social website provide no RSS or json interface, which make synchronizing among them rather difficult.

	把你的 人人 豆瓣 QQ签名 什么的，通通烧成RSS！


Known Bugs:
* URL for renren.com fail
* No planned support for QQ authentication
* nickname parse for QQ and douban fail

Limitation:
* though it's been tested on different servers with Apache and PHP 4.0 or higher. It's not garanteed that it would work fine. Never tested on older platforms.
* curl needed. this is faster and more stable than "file_get_content($URL)";

Setup Guide:
*)
the 3rd line "if ($pass!="12345"){" defines the access password.
change it with your own.
it's also possible, however, to delete the block:
------
$pass = $_GET["pass"];
if ($pass!="12345"){
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: http://blog.qooza.hk/phoeagon");
        exit(0);
}
-----
altogether. In that way, everyone, including spider program can access your RSS generator and your server will be forced to fulfill every request.

*)
the request url is like:
http://yoursite.here/w2rss.php?site=renren&id=123456&pass=12345
"pass=" gives the access password set above.
"site=" can be "douban", "qq", "163xq", etc.
"id=" is the ID of the particular user.


Any problem?
the one most likely to meet is the inavailability of curl(). The server may, disable curl() and file_get_content(), may restrict access to other servers or has a trival timeout limit. If the request failed to be fulfilled in time, the program gets nothing and will not generate a correct page.

