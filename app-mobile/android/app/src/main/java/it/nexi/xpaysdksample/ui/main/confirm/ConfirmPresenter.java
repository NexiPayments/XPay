package it.nexi.xpaysdksample.ui.main.confirm;

import it.nexi.xpaysdksample.ui.main.IMainContract;

public class ConfirmPresenter implements IConfirmContract.Presenter {

    private IConfirmContract.View mConfirmView;
    private IMainContract.Router mRouter;


    ConfirmPresenter (IConfirmContract.View view){
        super();
        mConfirmView = view;
    }

    @Override
    public void onAttached() {
        mRouter = mConfirmView.getRouter();
    }

    @Override
    public void goToFragment(Class fragmentClass) {
        mRouter.openFragment(fragmentClass, mConfirmView);
    }

    @Override
    public void showTotalPrice(int totalPrice) {
        mConfirmView.displayTotalPrice(totalPrice);
    }
}
