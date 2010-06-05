function send_to_form(param,param_value) {
    var frm=document.forms['viewform'];
    frm[param].value=param_value;
    frm.submit();
   return false;
}
