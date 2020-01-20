package com.innovitrix.napsamarketsales.model;

import android.widget.TextView;

import com.innovitrix.napsamarketsales.R;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;

public class MenuData {

    private int MenuId;
    private String MenuName;
    private int MenuImage;
    private int MenuBackgroundImage;

    public MenuData(int menuId, String menuName, int menuImage,  int menuBackgroundImage) {
        MenuId = menuId;
        MenuName = menuName;
        MenuImage = menuImage;
        MenuBackgroundImage = menuBackgroundImage;
    }


    public int getMenuBackgroundImage() {
        return MenuBackgroundImage;
    }

    public void setMenuBackgroundImage(int menuBackgroundImage) {
        MenuBackgroundImage = menuBackgroundImage;
    }


    public int getMenuId() {
        return MenuId;
    }

    public void setMenuId(int menuId) {
        MenuId = menuId;
    }

    public MenuData(int menuId, String menuName, int menuImage) {
        MenuId = menuId;
        MenuName = menuName;
        MenuImage = menuImage;
    }

    public String getMenuName() {
        return MenuName;
    }

    public void setMenuName(String menuName) {
        MenuName = menuName;
    }

    public int getMenuImage() {
        return MenuImage;
    }

    public void setMenuImage(int menuImage) {
        MenuImage = menuImage;
    }


}
