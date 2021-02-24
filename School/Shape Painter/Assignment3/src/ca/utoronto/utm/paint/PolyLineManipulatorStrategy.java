package ca.utoronto.utm.paint;

import javafx.scene.input.MouseButton;
import javafx.scene.input.MouseEvent;

public class PolyLineManipulatorStrategy extends ShapeManipulatorStrategy {
	PolyLineManipulatorStrategy(PaintModel paintModel) {
		super(paintModel);
	}

	private PolyLineCommand polyCommand = new PolyLineCommand();

	@Override
	public void mousePressed(MouseEvent e) {
		if(e.getButton() == MouseButton.PRIMARY) {
			this.polyCommand.add(new Point((int)e.getX(), (int)e.getY()));
			this.addCommand(polyCommand);
		}
		else {
			int moves = this.polyCommand.getPoints().size()-1;// the number of points
			//due to poly line's mecahnics there are n points and n-1 clicks
			while(moves > 1) {
				this.removeCommand();
				moves -=1;
			}
			this.polyCommand = new PolyLineCommand();
		}
	}
	
	public void mouseMoved(MouseEvent e) {
		this.polyCommand.remove();
		this.polyCommand.add(new Point((int)e.getX(), (int)e.getY()));
		//this.addCommand(polyCommand);
	}

}
