package it.nexi.xpaysdksample.ui.main.result;

import java.util.Date;

import it.nexi.xpay.CallBacks.ApiResponseCallback;
import it.nexi.xpay.Models.WebApi.Errors.ApiErrorResponse;
import it.nexi.xpay.Models.WebApi.Responses.BackOffice.ApiReportOrdiniResponse;
import it.nexi.xpay.Models.WebApi.Responses.BackOffice.Report;
import it.nexi.xpay.Utils.BackOffice.ReportOrdiniUtils;
import it.nexi.xpaysdksample.data.repository.payment.IPaymentRepository;
import it.nexi.xpaysdksample.ui.main.IMainContract;
import it.nexi.xpaysdksample.ui.main.home.HomeFrame;
import it.nexi.xpaysdksample.ui.main.nonce.NonceFrame;

public class ResultPresenter implements IResultContract.Presenter{

    private IResultContract.View mResultView;
    private IPaymentRepository mPaymentRepository;
    private IMainContract.Router mRouter;
    private String transactionCode = "FR"+System.currentTimeMillis();

    ResultPresenter (IResultContract.View view, IPaymentRepository paymentRepository){
        super();
        mResultView = view;
        mPaymentRepository = paymentRepository;
    }

    @Override
    public void onAttached() {
        mRouter = mResultView.getRouter();
    }

    @Override
    public void goToFragment(Class fragmentClass) {
        mRouter.openFragment(HomeFrame.class, mResultView);
    }

    @Override
    public void showTotalPrice(int totalPrice) {
        mResultView.displayTotalPrice(totalPrice);
    }

    // Call method ordersReport in paymentRepository and manage the outcome of the operation (onSuccess and onError) through ApiResponseCallback
    @Override
    public void ordersReport() {
        mPaymentRepository.ordersReport(new Date(), new Date(), ReportOrdiniUtils.Channel.ALL,
                new ReportOrdiniUtils.Status[]
                        {
                                ReportOrdiniUtils.Status.AUTHORIZED,
                                ReportOrdiniUtils.Status.UNAUTHORIZED,
                                ReportOrdiniUtils.Status.CANCELED
                        }, transactionCode,
                new ApiResponseCallback<ApiReportOrdiniResponse>() {
            @Override
            public void onSuccess(ApiReportOrdiniResponse apiReportOrdiniResponse) {
                // Create string with all orders report
                StringBuilder message = new StringBuilder();
                for (Report report : apiReportOrdiniResponse.getReports()) {
                    message.append("\n").append(report.getName());
                }
                mResultView.displayReportDialog(message.toString());
            }

            @Override
            public void onError(ApiErrorResponse apiErrorResponse) {
                mResultView.displayReportDialog(apiErrorResponse.getError().getMessage());
            }
        });

    }


}
