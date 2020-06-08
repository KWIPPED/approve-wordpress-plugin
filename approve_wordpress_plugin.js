//*****************************************************************************
//* KWIPPED 2020.
//* This file is  common to all wordpress solutions. If you change it, please
//* update all wordrpess solutions released by KWIPPED
//*****************************************************************************
window.kwipped_approve = window.kwipped_approve || {};
window.kwipped_approve.loader_url = php_vars.loader_url;
window.kwipped_approve.url = php_vars.approve_url;
window.kwipped_approve.approve_id= php_vars.approve_id;

//Drop tag onto page.
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName('head')[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.approveid = window.kwipped_approve.approve_id;
	js.kwippedurl = window.kwipped_approve.url;
	fjs.parentNode.insertBefore(js, fjs);
 }(document, 'approve-widget', 'kwipped-approve-plugin-tag'));


(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = window.kwipped_approve.loader_url;
	fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'kwipped-approve-plugin-code'));
 