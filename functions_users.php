<?php

define('USERS_XML', __DIR__ . '/users.xml');


function loadUsers() {
    if (!file_exists(USERS_XML)) {
        throw new Exception("users.xml not found");
    }
    return simplexml_load_file(USERS_XML);
}


function saveUsers($xml) {
    $tmp = tempnam(sys_get_temp_dir(), 'userxml');
    file_put_contents($tmp, $xml->asXML());
    rename($tmp, USERS_XML);
}


function findUser($username) {
    $xml = loadUsers();
    foreach ($xml->user as $u) {
        if ((string)$u->username === $username) return $u;
    }
    return null;
}


function registerUser($username, $password, $role = "user") {
    $xml = loadUsers();

    if (findUser($username)) {
        throw new Exception("User already exists");
    }

    $id = 1;
    foreach ($xml->user as $u) {
        $uid = intval($u['id']);
        if ($uid >= $id) $id = $uid + 1;
    }

    $user = $xml->addChild("user");
    $user->addAttribute("id", $id);
    $user->addChild("username", $username);
    $user->addChild("password", password_hash($password, PASSWORD_BCRYPT));
    $user->addChild("role", $role);

    saveUsers($xml);

    return $id;
}


function authenticate($username, $password) {
    $u = findUser($username);
    if (!$u) return false;
    if (!password_verify($password, (string)$u->password)) return false;
    return $u;
}
