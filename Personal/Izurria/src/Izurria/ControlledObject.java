package Izurria;


/*
 * @Author Miguel De Vera
 * @Version 2018-01-01
 */

import java.awt.*;

/**
 * A controlled object takes key presses from a controller
 * Assigns a controller to an object so it can move
 */
public abstract class ControlledObject extends GameObject implements Moveable {
  Controller control;
  
 /**
 * Constructor for a controlled object
 * uses the gameobject constructor but assigns a controller to it
 * @param x same as gameObject
 * @param y same as gameObject
 * @param color same as gameObject
 * @param control the controller of the object
 */
  public ControlledObject(int x, int y, Color color, Controller control) {
    super(x,y,color);
    this.control = control;
  }
}
