package it.nexi.xpaysdksample.ui.main.home.adapters;

import android.content.Context;
import androidx.recyclerview.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import com.squareup.picasso.Picasso;

import java.util.ArrayList;

import it.nexi.xpaysdksample.R;
import it.nexi.xpaysdksample.data.entity.Product;

public class ProductAdapter extends RecyclerView.Adapter<ProductAdapter.MyViewHolder> {
    private ArrayList<Product> mDataset;
    private Context mContext;

    // Provide a reference to the views for each data item
    // Complex data items may need more than one view per item, and
    // you provide access to all the views for a data item in a view holder
    public static class MyViewHolder extends RecyclerView.ViewHolder {

        public TextView productName;
        public TextView productPrice;
        public ImageView productImage;

        public MyViewHolder(View v) {
            super(v);
            productName = v.findViewById(R.id.productName);
            productPrice = v.findViewById(R.id.productPrice);
            productImage = v.findViewById(R.id.productImage);
        }
    }

    // Provide a suitable constructor (depends on the kind of dataset)
    public ProductAdapter(ArrayList<Product> myDataset, Context context) {
        mDataset = myDataset;
        mContext = context;
    }

    // Create new views (invoked by the layout manager)
    @Override
    public ProductAdapter.MyViewHolder onCreateViewHolder(ViewGroup parent,
                                                          int viewType) {
        LayoutInflater inflater = (LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View v = inflater.inflate(R.layout.cart_product, parent, false);
        return new MyViewHolder(v);
    }

    // Replace the contents of a view (invoked by the layout manager)
    @Override
    public void onBindViewHolder(MyViewHolder holder, int position) {
        Product p = mDataset.get(position);
        holder.productName.setText(p.name);
        holder.productPrice.setText(p.formattedPrice);
        String a = p.imageUrl;
        Picasso.get().load(p.imageUrl).into(holder.productImage);
    }

    // Return the size of your dataset (invoked by the layout manager)
    @Override
    public int getItemCount() {
        return mDataset.size();
    }
}
