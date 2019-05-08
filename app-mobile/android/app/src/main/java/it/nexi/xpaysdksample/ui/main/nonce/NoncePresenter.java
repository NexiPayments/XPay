package it.nexi.xpaysdksample.ui.main.nonce;


import android.os.Bundle;

import it.nexi.xpay.Utils.Exceptions.InvalidCardException;
import it.nexi.xpaysdksample.R;
import it.nexi.xpaysdksample.ui.main.IMainContract;
import it.nexi.xpaysdksample.ui.main.confirm.ConfirmFrame;

public class NoncePresenter implements  INonceContract.Presenter{

    private INonceContract.View mNonceView;
    private IMainContract.Router mRouter;
    private Bundle bundle;

    NoncePresenter(INonceContract.View view){
        super();
        mNonceView = view;

    }

    @Override
    public void onPayClick() {
        mNonceView.createNonce(mNonceView.getTotalPrice(), mNonceView.getTransCode());
    }

    @Override
    public void onSuccessCreateNonce(String nonce) {
        mNonceView.addNonceToBundle(nonce);
        mRouter.openFragment(ConfirmFrame.class, mNonceView);
    }

    @Override
    public void onErrorCreateNonce(String msg) {
        mNonceView.displayDialog(R.string.nonce_error_title, R.string.nonce_error_text, msg);
    }

    @Override
    public void onAttached() {
        mRouter = mNonceView.getRouter();
    }

    @Override
    public void onInvalidCardException() {
        mNonceView.displaySnackBar(R.string.invalid_card_data);
    }
}
