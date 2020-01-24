package it.nexi.xpaysdksample.ui.main.nonce;

import it.nexi.xpaysdksample.R;
import it.nexi.xpaysdksample.ui.main.IMainContract;
import it.nexi.xpaysdksample.ui.main.confirm.ConfirmFrame;

public class NoncePresenter implements  INonceContract.Presenter{

    private INonceContract.View mNonceView;
    private IMainContract.Router mRouter;

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
    public void onInvalidPan() {
        mNonceView.displaySnackBar(R.string.invalid_pan);
    }

    @Override
    public void onInvalidExpiry() {
        mNonceView.displaySnackBar(R.string.invalid_expiry_date);
    }

    @Override
    public void onInvalidCvv() {
        mNonceView.displaySnackBar(R.string.invalid_cvv);
    }
}
