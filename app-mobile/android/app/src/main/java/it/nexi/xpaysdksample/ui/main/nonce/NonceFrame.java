package it.nexi.xpaysdksample.ui.main.nonce;


import android.content.Context;
import android.content.DialogInterface;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;

import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.snackbar.Snackbar;

import it.nexi.xpay.CallBacks.ApiResponseCallback;
import it.nexi.xpay.CardFormView.CardFormViewMultiline;
import it.nexi.xpay.Models.WebApi.Errors.ApiErrorResponse;
import it.nexi.xpay.Models.WebApi.Responses.HostedPayments.ApiCreaNonceResponse;
import it.nexi.xpay.Utils.CurrencyUtils;
import it.nexi.xpay.Utils.Exceptions.DeviceRootedException;
import it.nexi.xpay.Utils.Exceptions.InvalidCardException;
import it.nexi.xpaysdksample.R;
import it.nexi.xpaysdksample.ui.main.IMainContract;
import it.nexi.xpaysdksample.utils.Constants;

import static it.nexi.xpaysdksample.utils.Constants.ENVIRONMENT;

public class NonceFrame extends Fragment implements INonceContract.View  {

    private View mView;
    private CardFormViewMultiline cardFormMultiline;
    private Bundle bundle;
    private INonceContract.Presenter mNoncePresenter = new NoncePresenter(this);

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        mView = inflater.inflate(R.layout.fragment_nonce, container, false);
        bundle = getArguments();

        cardFormMultiline = mView.findViewById(R.id.cardFormMultiline);
        FloatingActionButton buttonPayMultiline = mView.findViewById(R.id.button_pay_multiline);

        buttonPayMultiline.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                mNoncePresenter.onPayClick();
            }
        });

        return mView;
    }

    @Override
    public void onAttach(Context context) {
        super.onAttach(context);
        mNoncePresenter.onAttached();
    }

    @Override
    public String getNoErrorAvailable() {
        return getResources().getString(R.string.error_not_found);
    }

    public int getTotalPrice(){
        if (bundle != null)
            return bundle.getInt("totalPrice");
        else
            return 0;
    }

    public String getTransCode(){
        if (bundle != null)
            return bundle.getString("transactionCode");
        return "";
    }

    public void createNonce (int amount, String transCode){
        try {
            cardFormMultiline.createNonce(getContext(), Constants.ALIAS, Constants.SECRET_KEY, amount,
                    CurrencyUtils.EUR, transCode, ENVIRONMENT,
                    new ApiResponseCallback<ApiCreaNonceResponse>() {
                @Override
                public void onSuccess(ApiCreaNonceResponse apiCreaNonceResponse) {
                    mNoncePresenter.onSuccessCreateNonce(apiCreaNonceResponse.getNonce());
                }

                @Override
                public void onError(ApiErrorResponse apiErrorResponse) {
                    mNoncePresenter.onErrorCreateNonce(apiErrorResponse.getError().getMessage());
                }
            });
        } catch (DeviceRootedException e) {
            Log.e("XPAY", "Rooted device");
        } catch (InvalidCardException e){
            Log.e("XPAY", "Invalid card data insert.");
            mNoncePresenter.onInvalidCardException();
        }
    }

    public void addNonceToBundle(String nonce){
        bundle.putString("nonce", nonce);
    }

    @Override
    public void displaySnackBar(int idMsg) {
        Snackbar.make(mView, getResources().getString(idMsg), Snackbar.LENGTH_LONG)
                .setAction(R.string.cancel, new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {

                    }
                })
                .setActionTextColor(getResources().getColor(android.R.color.holo_red_light ))
                .show();
    }

    @Override
    public Bundle getBundle() {
        return bundle;
    }

    @Override
    public IMainContract.Router getRouter() {
        return (IMainContract.Router) getContext();
    }

    // Display dialog with custom message
    @Override
    public void displayDialog(int title, int text, String message) {
        new AlertDialog.Builder(getContext())
                .setTitle(title)
                .setMessage(getString(text) + message)
                .setPositiveButton(R.string.ok, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {

                            }
                        }
                )
                .show();
    }
}
