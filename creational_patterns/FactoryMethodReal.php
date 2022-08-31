<?php


interface SocialNetworkConnector
{
  public function logIn(): void;
  public function logOut(): void;
  public function createPost($content): void;
}

abstract class SocialNetworkPoster
{
  abstract public function getSocialNetwork(): SocialNetworkConnector;

  public function post($content): void
  {
    $network = $this->getSocialNetwork();
    $network->logIn();
    $network->createPost($content);
    $network->logOut();
  }
}

class FacebookPoster extends SocialNetworkPoster
{
  public function __construct(private string $username, private string $password)
  {
  }

  public function getSocialNetwork(): SocialNetworkConnector
  {
    return new FacebookConnector($this->username, $this->password);
  }
}


class FacebookConnector implements SocialNetworkConnector
{
  public function __construct(private string $username, private string $password)
  {
  }
  public function logIn(): void
  {
    echo "<h1>Welcome " . $this->username . "</h1>";
  }
  public function createPost($content): void
  {
    echo "<h1>New Post {$content}</h1>";
  }
  public function logOut(): void
  {
    echo "<h1>GoodBye " . $this->username . "</h1>";
  }
}

function useSocialMedia(SocialNetworkPoster $SocialNetworkPoster, string $content)
{
  $SocialNetworkPoster->getSocialNetwork();
  $SocialNetworkPoster->post($content);
}

useSocialMedia(new FacebookPoster('lschulzes', "secret"), "Happy Coding");
useSocialMedia(new FacebookPoster('hannabeans', "secreto"), "Happy MMS");
