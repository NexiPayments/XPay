package it.nexi.xpaysdksample.ui.main.home;

import it.nexi.xpay.CallBacks.FrontOfficeCallbackQP;
import it.nexi.xpay.CallBacks.GooglePayCallback;
import it.nexi.xpay.GooglePay.GooglePayRequest;
import it.nexi.xpay.GooglePay.IGooglePayListener;
import it.nexi.xpay.Models.WebApi.Errors.ApiErrorResponse;
import it.nexi.xpay.Models.WebApi.Responses.FrontOffice.ApiFrontOfficeQPResponse;
import it.nexi.xpay.Models.WebApi.Responses.GooglePay.ApiGooglePayResponse;
import it.nexi.xpaysdksample.R;
import it.nexi.xpaysdksample.data.entity.Product;
import it.nexi.xpaysdksample.data.network.response.ProductResponse;
import it.nexi.xpaysdksample.data.repository.order.IOrderRepository;
import it.nexi.xpaysdksample.data.repository.payment.IPaymentRepository;
import it.nexi.xpaysdksample.data.repository.product.IProductRepository;
import it.nexi.xpaysdksample.ui.main.IMainContract;
import it.nexi.xpaysdksample.ui.main.nonce.NonceFrame;
import it.nexi.xpaysdksample.utils.Constants;

import static it.nexi.xpaysdksample.utils.Constants.ENVIRONMENT;

public class HomePresenter implements IHomeContract.Presenter {

    private IHomeContract.View mHomeView;
    private IMainContract.Router mRouter;

    // Repositories
    private IProductRepository mProductRepository;
    private IPaymentRepository mPaymentRepository;
    private IOrderRepository mOrderRepository;

    private int totalPrice;
    private String transactionCode = "FR"+System.currentTimeMillis();

    HomePresenter(IHomeContract.View view, IProductRepository productRepository, IPaymentRepository paymentRepository, IOrderRepository orderRepository) {
        mHomeView = view;
        mProductRepository = productRepository;
        mPaymentRepository = paymentRepository;
        mOrderRepository = orderRepository;
    }

    @Override
    public void onAttached() {
        mRouter = mHomeView.getRouter();
    }

    @Override
    public void onHomeCreated() {
        ProductResponse products = loadAllProducts();
        // Display list of products with recycler view
        mHomeView.displayRecyclerView(products);
        // Display the total price of the cart
        showTotalPrice(products);
        // Check if Google Pay is available
        checkGooglePayAvailability();
    }

    @Override
    public void goToNonceActivity() {
        // Set bundle with transaction code and total amount to pass at NonceFrame
        mHomeView.setBundle(transactionCode, totalPrice);
        mRouter.openFragment(NonceFrame.class, mHomeView);
    }

    private ProductResponse loadAllProducts() {
        return mProductRepository.getAllProduct();
    }

    private void showTotalPrice(ProductResponse productResponse) {
        // Calculate total price of the cart (in cents)
        for (Product p : productResponse.products) {
            totalPrice += p.price*100;
        }
        mHomeView.displayTotalPrice(totalPrice);
    }

    // Check if Google Pay is available
    private void checkGooglePayAvailability() {
        mHomeView.checkGooglePay(ENVIRONMENT, mOrderRepository.getBillingFields(), new IGooglePayListener() {
            @Override
            public void onGooglePayAvailable(boolean available) {
                mHomeView.displayGooglePay(available);
            }
        });
    }

    @Override
    public void onGooglePayClick() {
        String countryCode = mOrderRepository.getCountry();
        // Get Google Pay request
        GooglePayRequest googlePayRequest = mPaymentRepository.getGooglePayRequest(Constants.MERCHANT_NAME, countryCode, totalPrice, transactionCode);
        // Set additional fields
        googlePayRequest.setBillingParameters(mOrderRepository.getBillingFields());
        googlePayRequest.setShippingParameters(mOrderRepository.getShippingFields());
        // Start Google Pay process
        mHomeView.startGooglePay(googlePayRequest, getGooglePayHandler());
    }

    @Override
    public void onPayClick() {
        mHomeView.displayChoiceDialog(R.string.choice_browser_method, R.string.choice_browser_method_text);
    }

    // Call pay method in paymentRepository manage the outcome of the operation (onConfirm and onCancel) through FrontOfficeQPCallback (ChromeTab)
    @Override
    public void onPayChromeTabClick() {
        transactionCode = "FR"+System.currentTimeMillis();
        mPaymentRepository.payChrome(transactionCode, totalPrice, getFrontOfficeCallbackQp());
    }

    // Call pay method in paymentRepository manage the outcome of the operation (onConfirm and onCancel) through FrontOfficeQPCallback (WebView)
    @Override
    public void onPayWebViewClick() {
        transactionCode = "FR"+System.currentTimeMillis();
        mPaymentRepository.pay(transactionCode, totalPrice, getFrontOfficeCallbackQp());
    }

    private FrontOfficeCallbackQP getFrontOfficeCallbackQp(){
        return new FrontOfficeCallbackQP() {
            @Override
            public void onConfirm(ApiFrontOfficeQPResponse apiFrontOfficeQPResponse) {
                if (apiFrontOfficeQPResponse.isValid()) {
                    mHomeView.displayDialog(R.string.qp_payment_success_title, R.string.qp_payment_success_text, apiFrontOfficeQPResponse.getBrand());
                } else {
                    String message = mHomeView.getNoErrorAvailable();
                    if (apiFrontOfficeQPResponse.getError() != null)
                        message = apiFrontOfficeQPResponse.getError().getMessage();
                    mHomeView.displayDialog(R.string.qp_payment_error_title, R.string.payment_error_text, message);
                }
            }

            @Override
            public void onError(ApiErrorResponse apiErrorResponse) {
                mHomeView.displayDialog(R.string.qp_payment_error_title, R.string.payment_error_text, apiErrorResponse.getError().getMessage());
            }

            @Override
            public void onCancel(ApiFrontOfficeQPResponse apiFrontOfficeQPResponse) {
                mHomeView.displayDialog(R.string.qp_payment_canceled_title, R.string.payment_canceled_text);
            }
        };
    }

    private GooglePayCallback getGooglePayHandler() {
        return new GooglePayCallback() {
            @Override
            public void onCancel() {
                mHomeView.displayDialog(R.string.google_pay, R.string.payment_canceled_text);
            }

            @Override
            public void onSuccess(ApiGooglePayResponse apiGooglePayResponse) {
                mHomeView.displayDialog(R.string.google_pay, R.string.user_paid_google, apiGooglePayResponse.getBrand());
            }

            @Override
            public void onError(ApiErrorResponse apiErrorResponse) {
                mHomeView.displayDialog(R.string.google_pay, R.string.payment_error_text, apiErrorResponse.getError().getMessage());
            }
        };
    }
}