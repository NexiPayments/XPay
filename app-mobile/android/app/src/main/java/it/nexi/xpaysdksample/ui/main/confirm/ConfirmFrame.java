package it.nexi.xpaysdksample.ui.main.confirm;

import android.content.Context;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.fragment.app.Fragment;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import it.nexi.xpaysdksample.R;
import it.nexi.xpaysdksample.ui.main.IMainContract;
import it.nexi.xpaysdksample.ui.main.home.HomeFrame;
import it.nexi.xpaysdksample.ui.main.result.ResultFrame;

public class ConfirmFrame extends Fragment implements IConfirmContract.View {

    private View mView;
    private int amount;
    private Bundle bundle;
    private IConfirmContract.Presenter mConfirmPresenter;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        mConfirmPresenter = new ConfirmPresenter(this);
        mView = inflater.inflate(R.layout.fragment_confirm, container, false);
        mConfirmPresenter.onAttached();

        // Get nonce from nonceActivity bundle
        bundle = getArguments();
        if (bundle != null) {
            amount = bundle.getInt("totalPrice");
            String nonce = bundle.getString("nonce");
        }

        // Confirm_button listener
        FloatingActionButton confirmPayment = mView.findViewById(R.id.button_confirm);
        confirmPayment.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                // TODO: Server to server call
                mConfirmPresenter.goToFragment(ResultFrame.class);
            }
        });

        mConfirmPresenter.showTotalPrice(amount);

        // Cancel_button listener
        FloatingActionButton cancelPayment = mView.findViewById(R.id.button_cancel);
        cancelPayment.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                mConfirmPresenter.goToFragment(HomeFrame.class);
            }
        });

        return mView;
    }

    @Override
    public void onAttach(Context context) {
        super.onAttach(context);
    }


    @Override
    public String getNoErrorAvailable() {
        return getResources().getString(R.string.error_not_found);
    }

    // Display cart total price
    @Override
    public void displayTotalPrice(int totalPrice) {
        TextView text = mView.findViewById(R.id.text_amount);
        text.setText("€ "+String.format("%.2f", ((float)totalPrice/100)));
    }

    @Override
    public IMainContract.Router getRouter() {
        return (IMainContract.Router) getContext();
    }

    @Override
    public Bundle getBundle() {
        return bundle;
    }
}
