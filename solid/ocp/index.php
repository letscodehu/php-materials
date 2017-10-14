<?php 

function randomBool() {
    return rand(0,1) == 1;
}

class SubscriptionService {

    public function isSubscribed($email) {
        $subscribed = randomBool();
        if ($subscribed) {
            echo $email. ' is already subscribed!'. PHP_EOL;
        }
        return $subscribed;
    }
    public function eligibleForExtendedTrial($email) {
        $eligible = randomBool();
        if ($eligible) {
            echo $email. ' is eligible for extended trial!'. PHP_EOL;
        }
        return $eligible;
    }
    public function subscribeForTrial($email, $days) {
        echo $email. ' is subscribed for '.$days.' days!'. PHP_EOL;
    }

}

class FeatureManager {

    public function isActive($feature) {
        $active = randomBool();
        if ($active) {
            echo $feature. ' is active!'. PHP_EOL;
        }
        return $active;
    }

}


class SocialSigninAdapter {

    private $strategyChain;

    public function __construct(TrialSubscriptionStrategyChain $strategyChain) {
        $this->strategyChain = $strategyChain;
    }

    public function subscribeIfNewUser($email) {
        $this->strategyChain->subscribe($email);
    }

}

class TrialSubscriptionStrategyChain {

    private $strategies = [];

    public function add(AbstractSubscriptionStrategy $strategy) {
        $last = end($this->strategies);
        if ($last != null) {
            $last->setNext($strategy);
        }
        $this->strategies[] = $strategy;
    }

    public function subscribe($email) {
        $first = reset($this->strategies);
        if ($first != null) {
            $first->subscribe($email);
        }
    }

}

abstract class AbstractSubscriptionStrategy {
    protected $next;
    protected $subscriptionService;
    protected $featureManager;

    public function __construct(SubscriptionService $subscriptionService, 
    FeatureManager $featureManager) {
        $this->featureManager = $featureManager;
        $this->subscriptionService = $subscriptionService;
    }

    public function setNext(AbstractSubscriptionStrategy $strategy) {
        $this->next = $strategy;
    }

    protected function callNext($email) {
        if ($this->next != null) {
            $this->next->subscribe($email);
        }
    }

    public abstract function subscribe($email);

}

class AlreadySubscribedStrategy extends AbstractSubscriptionStrategy {

    public function subscribe($email) {
        if (!$this->subscriptionService->isSubscribed($email)) {
            $this->callNext($email);
        }
    }
}

class ExtendTrialSubscriptionStrategy extends AbstractSubscriptionStrategy {
    
    public function subscribe($email) {
        if ($this->subscriptionService->eligibleForExtendedTrial($email)) {
            $this->subscriptionService->subscribeForTrial($email, 14);
        } else {
            $this->callNext($email);
        }
    }
}


class OneWeekTrialSubscriptionStrategy extends AbstractSubscriptionStrategy {
    
    public function subscribe($email) {
        if ($this->featureManager->isActive("ONE_WEEK_TRIAL")) {
            $this->subscriptionService->subscribeForTrial($email, 7);
        } else {
            $this->callNext($email);
        }
    }
}


class OneDayTrialSubscriptionStrategy extends AbstractSubscriptionStrategy {
    
    public function subscribe($email) {
        if ($this->featureManager->isActive("ONE_DAY_TRIAL")) {
            $this->subscriptionService->subscribeForTrial($email, 1);
        } else {
            $this->callNext($email);
        }
    }
}

$subscriptionService = new SubscriptionService;
$featureManager = new FeatureManager;
$strategyChain = new TrialSubscriptionStrategyChain;
$strategyChain->add(new AlreadySubscribedStrategy($subscriptionService, $featureManager));
$strategyChain->add(new ExtendTrialSubscriptionStrategy($subscriptionService, $featureManager));
$strategyChain->add(new OneDayTrialSubscriptionStrategy($subscriptionService, $featureManager));
$strategyChain->add(new OneWeekTrialSubscriptionStrategy($subscriptionService, $featureManager));
$signinAdapter = new SocialSigninAdapter($strategyChain);
$signinAdapter->subscribeIfNewUser("fejlesztes@letscode.hu");