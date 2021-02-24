package ca.utoronto.utm.paint;

import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.Observable;
import java.util.Observer;

import javafx.scene.canvas.GraphicsContext;

public class PaintModel extends Observable implements Observer {
	public String toString() {
		PaintCommand holder;
		String result = "Paint Save File Version 1.0\n";
		for(int i = 0; i < this.commands.size();i++) {
			holder = this.commands.get(i);
			if(holder instanceof CircleCommand)
				result += printCircle((CircleCommand) holder);
			else if(holder instanceof RectangleCommand)
				result += printRectangle((RectangleCommand) holder);
			else if(holder instanceof SquiggleCommand)
				result += printSquiggle((SquiggleCommand) holder);
			else if(holder instanceof PolyLineCommand)
				result += printPoly((PolyLineCommand) holder);
		}
		result += "End Paint Save File";
		return result;
	}
	//object instanceof CircleCommand
	//above checks if the object taken is of class whatever
	public String printCircle(CircleCommand c) {
		String result = "Circle\n";
		result += "	color:" + colorString(c) + "\n";
		result += "	filled:" + c.isFill() + "\n";
		result += "	center:" +"(" +c.getCentre().x +","+c.getCentre().y+")" + "\n";
		result += "	radius:" + c.getRadius() + "\n";
		result += "End Circle\n";
		return result;
	}
	public String printRectangle(RectangleCommand c) {
		String result = "Rectangle\n";
		result += "	color:" + colorString(c) + "\n";
		result += "	filled:" + c.isFill() + "\n";
		result += " 	p1:" +"(" + c.getP1().x +","+c.getP1().y +")" + "\n";
		result += " 	p2:" +"(" + c.getP2().x +","+c.getP2().y +")" + "\n";
		result += "End Rectangle\n";
		return result;
	}
	public String printSquiggle(SquiggleCommand c) {
		String result = "Squiggle\n";
		result += "	color:" + colorString(c) + "\n";
		result += "	filled:" + c.isFill() + "\n";
		result += "	points\n";
		for(int i = 0; i < c.getPoints().size();i++) {
			Point point = c.getPoints().get(i);
			result += "		point:(" + point.x + ","+ point.y + ")\n";
		}
		result += "	end points\n";
		result += "End Squiggle\n";
		return result;
	}
	public String printPoly(PolyLineCommand c) {
		String result = "Polyline\n";
		result += "	color:" + colorString(c) + "\n";
		result += "	filled:" + c.isFill() + "\n";
		result += "	points\n";
		for(int i = 0; i < c.getPoints().size();i++) {
			Point point = c.getPoints().get(i);
			result += "		point:(" + point.x + ","+ point.y + ")\n";
		}
		result += "	end points\n";
		result += "End Polyline\n";
		return result;
	}
	public String colorString(PaintCommand c) {
		return c.getColorValues();
	}
	
	///////////////////////////////////////////////////////////////////////////
	public void reset(){
		for(PaintCommand c: this.commands){
			c.deleteObserver(this);
		}
		this.commands.clear();
		this.setChanged();
		this.notifyObservers();
	}
	
	public void addCommand(PaintCommand command){
		this.commands.add(command);
		command.addObserver(this);
		this.setChanged();
		this.notifyObservers();
	}
	
	public void removeCommand() {
		this.commands.remove(this.commands.size()-1);
	}
	
	private ArrayList<PaintCommand> commands = new ArrayList<PaintCommand>();

	public void executeAll(GraphicsContext g) {
		for(PaintCommand c: this.commands){
			c.execute(g);
		}
	}
	
	/**
	 * We Observe our model components, the PaintCommands
	 */
	@Override
	public void update(Observable o, Object arg) {
		this.setChanged();
		this.notifyObservers();
	}
}
