package it.nexi.xpaysdksample.ui.main.home;


import android.content.DialogInterface;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.RelativeLayout;
import android.widget.TextView;


import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import org.json.JSONObject;

import java.util.ArrayList;

import it.nexi.xpay.CallBacks.GooglePayCallback;
import it.nexi.xpay.GooglePay.GooglePayRequest;
import it.nexi.xpay.GooglePay.IGooglePayListener;
import it.nexi.xpay.Utils.EnvironmentUtils;
import it.nexi.xpaysdksample.R;
import it.nexi.xpaysdksample.data.network.product.WebServiceProduct;
import it.nexi.xpaysdksample.data.entity.Product;
import it.nexi.xpaysdksample.data.network.response.ProductResponse;
import it.nexi.xpaysdksample.data.repository.order.OrderRepository;
import it.nexi.xpaysdksample.data.repository.payment.PaymentRepository;
import it.nexi.xpaysdksample.data.repository.product.ProductRepository;
import it.nexi.xpaysdksample.ui.main.IMainContract;
import it.nexi.xpaysdksample.ui.main.home.adapters.ProductAdapter;

public class HomeFrame extends Fragment implements IHomeContract.View {

    private View mView;
    private IHomeContract.Presenter mHomePresenter;
    private IMainContract.View activityCallback;
    private RelativeLayout googlePayButton;
    private Bundle fragmentBundle;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        mHomePresenter= new HomePresenter(this,
                new ProductRepository(new WebServiceProduct()),
                new PaymentRepository(getActivity()),
                new OrderRepository());

        activityCallback = (IMainContract.View) getContext();
        mHomePresenter.onAttached();

        // Inflate view
        mView = inflater.inflate(R.layout.content_home, container, false);

        // Button_pay listener
        Button pay = mView.findViewById(R.id.button_pay);
        pay.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //mHomePresenter.pay();
                mHomePresenter.onPayClick();
            }
        });

        // Button_pay_nonce listener
        Button payNonce = mView.findViewById(R.id.button_pay_nonce);
        payNonce.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                mHomePresenter.goToNonceActivity();
            }
        });

        googlePayButton = mView.findViewById(R.id.button_googlepay);
        googlePayButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mHomePresenter.onGooglePayClick();
            }
        });
        mHomePresenter.onHomeCreated();
        return mView;
    }

    @Override
    public String getNoErrorAvailable() {
        return getResources().getString(R.string.error_not_found);
    }

    // Display the total price of the cart
    @Override
    public void displayTotalPrice(int totalPrice) {
        TextView text = mView.findViewById(R.id.totalPrice);
        String price = String.format("%.2f", ((float)totalPrice/100));
        text.setText(getString(R.string.total_price,  price));
    }

    @Override
    public void setBundle(String transactionCode, int amount) {
        fragmentBundle = new Bundle();
        fragmentBundle.putString("transactionCode", transactionCode);
        fragmentBundle.putInt("totalPrice", amount);
    }

    @Override
    public IMainContract.Router getRouter() {
        return (IMainContract.Router) getContext();
    }

    // Display the list with all products in the cart
    @Override
    public void displayRecyclerView(ProductResponse productResponse){

        ArrayList<Product> mProducts = productResponse.products;
        RecyclerView mRecyclerView = mView.findViewById(R.id.recyclerView);

        // Use this setting to improve performance if you know that changes
        // in content do not change the layout size of the RecyclerView
        mRecyclerView.setHasFixedSize(true);

        // Use a linear layout manager
        RecyclerView.LayoutManager mLayoutManager = new LinearLayoutManager(mView.getContext());
        mRecyclerView.setLayoutManager(mLayoutManager);

        // Specify an adapter
        RecyclerView.Adapter mAdapter = new ProductAdapter(mProducts, mView.getContext());
        mRecyclerView.setAdapter(mAdapter);
    }

    @Override
    public void displayDialog(int title, int text) {
        displayDialog(title, text, "");
    }

    // Display dialog with custom message
    @Override
    public void displayDialog(int title, int text, String message) {
        new AlertDialog.Builder(mView.getContext())
                .setTitle(title)
                .setMessage(getString(text) + message)
                .setPositiveButton(R.string.ok, null)
                .show();
    }

    @Override
    public void displayChoiceDialog(int title, int text){
        new AlertDialog.Builder(mView.getContext())
                .setTitle(title)
                .setMessage(getString(text))
                .setPositiveButton(R.string.pay_chrome_tab, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        mHomePresenter.onPayChromeTabClick();
                    }
                })
                .setNegativeButton(R.string.pay_web_view, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        mHomePresenter.onPayWebViewClick();
                    }
                })
                .show();
    }


    @Override
    public void displayGooglePay(boolean enabled) {
        googlePayButton.setEnabled(enabled);
    }

    @Override
    public void startGooglePay(GooglePayRequest googlePayRequest, GooglePayCallback googlePayCallback) {
        activityCallback.payWithGoogle(getContext(), googlePayRequest, googlePayCallback);
    }

    @Override
    public void checkGooglePay(EnvironmentUtils.Environment selectedEnvironment, JSONObject billingParameters, IGooglePayListener googlePayListener) {
        activityCallback.checkGooglePayAvailability(selectedEnvironment, billingParameters, googlePayListener);
    }

    @Override
    public Bundle getBundle() {
        return fragmentBundle;
    }
}
