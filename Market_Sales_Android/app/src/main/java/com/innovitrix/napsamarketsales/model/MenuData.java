package com.innovitrix.napsamarketsales.model;

public class MenuData {

   private int MenuId;
    private String MenuName;

    private int MenuImage ;

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
