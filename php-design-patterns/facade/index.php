<?php

class PaymentService {
    public function handlePayment(PaymentRequest $paymentRequest) {
        return new PaymentResponse;
    }
}

class PaymentRequest {

}

class PaymentResponse {

    private $paymentSuccessful = true;

    public function isPaymentSuccessful() {
        return $this->paymentSuccessful;
    }

}


class AvailabilityQueryService {

    public function checkAvailability(AvailabilityRequest $availabilityRequest) {
        return new AvailabilityResponse;
    }

}

class AvailabilityRequest {

}

class AvailabilityResponse {

    private $hasRooms = true;

    public function hasRooms() {
        return $this->hasRooms;
    }
    
}

class OrderService {

    public function createOrder(CreateOrderRequest $createOrderRequest) {
        return new CreateOrderResponse;
    }

    public function cancelOrder(CancelOrderRequest $createOrderRequest) {
        return new CancelOrderResponse;
    }

}

class CreateOrderResponse {

    private $orderNumber = 8155;

    public function getOrderNumber() {
        return $this->orderNumber;
    }

}
class CreateOrderRequest {}    
    
class CancelOrderResponse {}
class CancelOrderRequest {}    

class BillingService {

    public function createBill(BillingRequest $billingRequest) {
        return new BillingResponse;
    }

}

class BillingRequest {}
class BillingResponse {

    private $downloadUrl = "someUrl";

    public function getDownloadUrl() {
        return $this->downloadUrl;
    }

}

class ConfirmationMailCommand {

    private $orderNumber;
    private $downloadUrl;

    public function __construct($orderNumber, $downloadUrl) {
        $this->orderNumber = $orderNumber;
        $this->downloadUrl = $downloadUrl;
    }

    public function getDownloadUrl() {
        return $this->downloadUrl;
    }

    public function getOrderNumber() {
        return $this->orderNumber;
    }

}

class ConfirmationMailSender {

    public function sendConfirmation(ConfirmationMailCommand $mailCommand) {
        echo 'Mail sent: '.$mailCommand->getOrderNumber(). " " .$mailCommand->getDownloadUrl().PHP_EOL;
    } 

}

class BookingFacade {

    private $billingService, $orderService, $paymentService, $availabilityService, $confirmationSender;

    public function __construct(BillingService $billingService, OrderService $orderService, 
        PaymentService $paymentService, AvailabilityQueryService $availabilityService,
        ConfirmationMailSender $confirmationSender) {
        $this->billingService = $billingService;
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
        $this->availabilityService = $availabilityService;
        $this->confirmationSender = $confirmationSender;
    }

    public function book(BookingRequest $bookingRequest) {

        $availabilityResponse = $this->availabilityService->checkAvailability(new AvailabilityRequest);
        if (!$availabilityResponse->hasRooms()) {
            return "some availability error";
        }
        $createOrderResponse = $this->orderService->createOrder(new CreateOrderRequest);
        $paymentResponse = $this->paymentService->handlePayment(new PaymentRequest);
        if (!$paymentResponse->isPaymentSuccessful()) {
            $this->orderService->cancelOrder(new CancelOrderRequest);
            return 'some payment error';
        }
        $billingResponse = $this->billingService->createBill(new BillingRequest);
        $this->confirmationSender->sendConfirmation(new ConfirmationMailCommand($createOrderResponse->getOrderNumber(),
            $billingResponse->getDownloadUrl()));
        return new BookingResponse;        
    }

}

class BookingRequest {
}

class BookingResponse {
}

$confirmationSender = new ConfirmationMailSender;
$billingService = new BillingService;
$orderService = new OrderService;
$paymentService = new PaymentService;
$availabilityService = new AvailabilityQueryService;

$bookingFacade = new BookingFacade($billingService, $orderService, $paymentService, $availabilityService, $confirmationSender);
$bookingFacade->book(new BookingRequest);
