<?php
function get_user_by_email(PDO $pdo, $email){
return get_user($pdo, $email);
}

function is_pwd_wrong(string $iPwd, string $oPwdHash) : bool{
if(!password_verify($iPwd, $oPwdHash)){
    return true;
}

return false;
}
