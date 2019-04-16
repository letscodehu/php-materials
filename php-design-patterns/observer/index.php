<?php 

class Post {

}

class PostRepository {

    public function save(Post $post) {
        echo "Saving the post". PHP_EOL;
    }

}

interface PostServiceObserver {
    public function notify(Post $post);
}

abstract class PostObservable {

    protected $observers = [];

    public function attach(PostServiceObserver $observer) {
        $this->observers[] = $observer;
    }

    protected function notify(Post $post) {
        foreach ($this->observers as $observer) {
            $observer->notify($post);
        }
    }

}


class PostService extends PostObservable {

    private $postRepository;

    public function __construct(PostRepository $postRepository) {
        $this->postRepository = $postRepository;
    }

    public function publish(Post $post) {
        $this->postRepository->save($post);
        $this->notify($post);
    }

}

class FacebookPublisher implements PostServiceObserver {

    public function notify(Post $post) {
        $this->publishToPage($post);
    }

    public function publishToPage(Post $post) {
        echo 'Publish to facebook'. PHP_EOL;
    }

}

class EmailPublisher implements PostServiceObserver {

    public function notify(Post $post) {
        $this->sendNotificationMail($post);
    }

    public function sendNotificationMail(Post $post) {
        echo 'Sending e-mail'. PHP_EOL;
    }
    
}


class TwitterPublisher implements PostServiceObserver {

    public function notify(Post $post) {
        $this->tweet($post);
    }

    public function tweet(Post $post) {
        echo 'Publishing to twitter'. PHP_EOL;
    }
    
}

$service = new PostService(new PostRepository);
$service->attach(new TwitterPublisher);
$service->publish(new Post);
