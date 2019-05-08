package it.nexi.xpaysdksample.ui.main.result;

import android.content.DialogInterface;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.app.AlertDialog;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import it.nexi.xpaysdksample.R;
import it.nexi.xpaysdksample.data.repository.payment.PaymentRepository;
import it.nexi.xpaysdksample.ui.main.IMainContract;
import it.nexi.xpaysdksample.ui.main.home.HomeFrame;

public class ResultFrame extends Fragment implements IResultContract.View {

    private View mResultView;
    private IResultContract.Presenter mResultPresenter;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        mResultView = inflater.inflate(R.layout.fragment_result, container, false);

        // Button_done listener
        Button confirmPayment = mResultView.findViewById(R.id.button_done);
        confirmPayment.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                mResultPresenter.goToFragment(HomeFrame.class);
            }
        });

        // Button_orders listener
        Button cancelPayment = mResultView.findViewById(R.id.button_orders);
        cancelPayment.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                mResultPresenter.ordersReport();
            }
        });

        mResultPresenter = new ResultPresenter(this, new PaymentRepository(getContext()));
        mResultPresenter.onAttached();

        // Get total price from bundle
        Bundle bundle = getArguments();
        int totalPrice;
        totalPrice = bundle.getInt("totalPrice");

        mResultPresenter.showTotalPrice(totalPrice);

        return mResultView;
    }

    @Override
    public String getNoErrorAvailable() {
        return getResources().getString(R.string.error_not_found);
    }

    // Display dialog with orders report
    @Override
    public void displayReportDialog(String reports){
        new AlertDialog.Builder(mResultView.getContext())
                .setTitle(R.string.orders_report_title)
                .setMessage(reports)
                .setPositiveButton(R.string.ok, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {

                    }
                })
                .show();
    }

    @Override
    public IMainContract.Router getRouter() {
        return (IMainContract.Router) getContext();
    }

    // Display cart total price
    @Override
    public void displayTotalPrice(int totalPrice) {
        TextView text = mResultView.findViewById(R.id.text_totalPrice);
        text.setText("€ "+String.format("%.2f", ((float)totalPrice/100)));
    }

    @Override
    public Bundle getBundle() {
        return null;
    }
}
