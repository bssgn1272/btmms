package com.innovitrix.napsamarketsales.network;

import android.content.Context;
import android.graphics.Bitmap;

import com.android.volley.RequestQueue;
import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.Volley;
import com.bumptech.glide.util.LruCache;

/**
 * Created by Techgeek n Gamerboy on 07-Mar-18.
 */

public class VolleySingleton {

    /*
    * As any app requires many network operations. So in this case it is always a good idea to use a single instance of Volley RequestQueue.
    * For this we will use a Singleton Pattern for handling all the request queues.
    * */
    private static VolleySingleton instance;
    private RequestQueue requestQueue;
    private ImageLoader imageLoader;

    private VolleySingleton(Context context) {
        requestQueue = Volley.newRequestQueue(context);
        //requestQueue = new RequestQueue(new NoCache(), new BasicNetwork(new HurlStack()));


        imageLoader = new ImageLoader(requestQueue, new ImageLoader.ImageCache() {
            private final LruCache<String, Bitmap> cache = new LruCache<String, Bitmap>(20);


            @Override
            public Bitmap getBitmap(String url) {
                return cache.get(url);
            }

            @Override
            public void putBitmap(String url, Bitmap bitmap) {
                cache.put(url, bitmap);
            }
        });
    }


    public static VolleySingleton getInstance(Context context) {
        if (instance == null) {
            instance = new VolleySingleton(context);
        }
        return instance;
    }

    public RequestQueue getRequestQueue() {
        requestQueue.getCache().clear();

        return requestQueue;
    }

    public ImageLoader getImageLoader() {
        return imageLoader;
    }

}
