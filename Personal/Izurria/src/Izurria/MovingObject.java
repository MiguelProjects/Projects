package Izurria;


/**
 * @Author Miguel De Vera
 * @Version 2018-01-01
 */

import java.awt.Color;

/**
 * a moving object that is never directly used
 * has x and y speeds as the object WILL move
 * xSp and ySp is the value that the object will move
 */
public abstract class MovingObject extends GameObject implements Moveable {
  protected int xSp, ySp;//meaning Speed
  
  /**
   * Constructor for moving object
   * same parameters as gameobject except xSp and ySp
   * @param x is the horizontal coordinate
   * @param y is the vertical coordinate
   * @param xSp is the value that the object will move by in the horizontal direction
   * @param ySp is the value that the object will move by in the vertical direction
   * @param color is the color of the object
   */
  public MovingObject(int x, int y, int xSp, int ySp, Color color) {
    super(x, y, color);
    this.xSp = xSp;
    this.ySp = ySp;
  }
  
  /**
   * override
   * Constructor for moving object
   * move method that moves the object based on its "speed" variables
   */
  public void move() {
    // TODO Auto-generated method stub
    this.x += xSp;
    this.y += ySp;
  }
  /**
   * @return the x speed of the object
   */
  public int getXSpeed(){
    return xSp;
  }
  /**
   * @return the y speed of the object
   */
  public int getYSpeed(){
    return ySp;
  }
  /**
   * changes an objects horizontal speed
   * @param xSpeed sets the new value of the objects speed variable
   */
  public void setXSpeed(int xSpeed){
    this.xSp = xSpeed;
  }
   /**
   * changes an objects vertical speed
   * @param ySpeed sets the new value of the objects speed variable
   */
  public void setYVelocity(int ySpeed){
    this.ySp = ySpeed;
  }
}
