package Izurria;


import java.awt.Color;
import java.awt.*;

/**
 * the parent class of ALL game objects
 * will be drawn later on but not as a base gameobject
 * posses coordinates (x and y) and a bolean to see if its collding with anything
 */
public abstract class GameObject implements Drawable{//drawable used in ship, bullet and enemy, saves time here
  
  int x, y;
  Color color;
  Boolean isIntersecting;
  
  /**
   * constructor for the gameobject
   * @param xPos is the horizontal location
   * @param yPos is the vertical location
   * @param color is the color of the object
   */
  public GameObject(int xPos, int yPos,Color color){
    // TODO Auto-generated constructor stub
    this.x = xPos;
    this.y = yPos;
    this.color = color;
  }
  
  /**
   * abstract method that will be overriden later
   * @return the rectangle representing the boundry of an object
   */
  public abstract Rectangle getBounds();
  
  /**
   * @return the x location of the object
   */
  public int getX() {
    return x;
  }
  /**
   * @return the y location of the object
   */
  public int getY() {
    return y;
  }
  /**
   * sets the x location of an object
   * @param x is the new x location of the object
   */
  public void setX(int x) {
    this.x = x;
  }
  /**
   * sets the y location of an object
   * @param y is the new y location of the object
   */
  public void setY(int y) {
    this.y = y;
  }
  /**
   * @return the object's color
   */
  public Color getColor() {
    return color;
  }
  /**
   * @param color sets the object's color
   */
  public void setColor(Color color){
    this.color = color;
  }
  /**
   * checks to see if this object is intersecting with another object
   * @param p is the instance of the game object
   * @return isIntersecting a boolean if the object is coliding with anything
   */
  public boolean isIntersecting(GameObject p) {
    isIntersecting = p.getBounds().intersects(this.getBounds());
    return isIntersecting;
  }
  
  
}
