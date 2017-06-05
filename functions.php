<?php
//FUNCTIONS

function get_auth_link($redirect_uri)
{
    $HOST_NAME = 'https://oauth.vk.com/authorize';

    $client_id      = '6060840';
    $display        = 'page';
    $response_type  = 'code';
    $scope          = 'friends';
    $v              = 5.52;

    $query_url = http_build_query(array(
        'client_id'     => $client_id,
        'display'       => $display,
        'redirect_uri'  => $redirect_uri,
        'scope'         => $scope,
        'response_type' => $response_type,
        'v'             => $v
    ));

    return $HOST_NAME . '?' . $query_url;
}

function curl_post($host_name, $postfields)
{
    if( $curl = curl_init() ) {
        curl_setopt($curl, CURLOPT_URL, $host_name);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);

        $out = curl_exec($curl);

        if ($out)
        {
            return $out;
        }
        else
        {
            return false;
        }

        curl_close($curl);
    }
}

function get_access_token($redirect_uri, $code)
{
    $HOST_NAME = 'https://oauth.vk.com/access_token';

    $client_id      = '6060840';
    $client_secret  = 'dbmoQ7UKXgCbkvTNYxAR';

    $postfields = array(
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri'  => $redirect_uri,
        'code'          => $code
    );

    $out = curl_post($HOST_NAME, $postfields);
    $out_array = json_decode($out, true);

    return $out_array;
}

function get_friends($access_token, $count)
{
    $HOST_NAME = 'https://api.vk.com/method/';
    $method_name = 'friends.get';
    $HOST_NAME = $HOST_NAME . $method_name;

    $fields = 'name';

    $postfields = array(
        'count'        => $count,
        'fields'       => $fields,
        'access_token' => $access_token
    );

    $out = curl_post($HOST_NAME, $postfields);

    return json_decode($out, true)['response'];
}

function show_friends($friends)
{
    echo '<ol>';
    foreach ($friends as $friend)
    {
        echo '<li><a class="friend" href="' . htmlentities( 'https://vk.com/id' . $friend['user_id']) . '"> ' . $friend['first_name'] . ' ' . $friend['last_name'] . ' </a></li>';
    }
    echo '</ol>';
}

function get_user_info($uid)
{
    $HOST_NAME = 'https://api.vk.com/method/';
    $method_name = 'users.get';
    $HOST_NAME = $HOST_NAME . $method_name;

    $fields = 'name';

    $postfields = array(
        'user_ids' => $uid,
        'fields'   => $fields
    );

    $out = curl_post($HOST_NAME, $postfields);

    return json_decode($out, true)['response'][0];
}

function MAIN($access_token, $uid)
{
    $user_info = get_user_info($uid);

    echo 'Друзья пользователя <a class="user" href="' . htmlentities( 'https://vk.com/id' . $uid) . '"> ' . $user_info['first_name'] . ' ' . $user_info['last_name'] . ' </a>';
    $friends = get_friends($access_token, 5);

    show_friends($friends);
}

?>