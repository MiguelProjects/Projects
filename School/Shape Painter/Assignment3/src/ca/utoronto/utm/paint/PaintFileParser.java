package ca.utoronto.utm.paint;

import java.io.BufferedReader;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import javafx.scene.paint.Color;
/**
 * Parse a file in Version 1.0 PaintSaveFile format. An instance of this class
 * understands the paint save file format, storing information about
 * its effort to parse a file. After a successful parse, an instance
 * will have an ArrayList of PaintCommand suitable for rendering.
 * If there is an error in the parse, the instance stores information
 * about the error. For more on the format of Version 1.0 of the paint 
 * save file format, see the associated documentation.
 * 
 * @author 
 *
 */
public class PaintFileParser {
	private int lineNumber = 0; // the current line being parsed
	private String errorMessage =""; // error encountered during parse
	private PaintModel paintModel; 
	
	/**
	 * Below are Patterns used in parsing 
	 */
	private Pattern pFileStart=Pattern.compile("^PaintSaveFileVersion1.0$");
	private Pattern pFileEnd=Pattern.compile("^EndPaintSaveFile$");
	
	private Pattern pColor=Pattern.compile("^color:([0-9]{1,3}),([0-9]{1,3}),([0-9]{1,3})$");
	private Pattern pFilled=Pattern.compile("^filled:(true|false)$");

	private Pattern pCircleStart=Pattern.compile("^Circle$");
	private Pattern pCenter=Pattern.compile("^center:\\((-*[0-9]{1,3}),(-*[0-9]{1,3})\\)$");
	private Pattern pRadius=Pattern.compile("^radius:([0-9]{1,9})$");
	private Pattern pCircleEnd=Pattern.compile("^EndCircle$");
	
	private Pattern rRectangleStart=Pattern.compile("^Rectangle$");
	private Pattern rP1=Pattern.compile("^p1:\\((-*[0-9]{1,3}),(-*[0-9]{1,3})\\)$");
	private Pattern rP2=Pattern.compile("^p2:\\((-*[0-9]{1,3}),(-*[0-9]{1,3})\\)$");
	private Pattern rRectangleEnd=Pattern.compile("^EndRectangle$");
	
	private Pattern pointStart=Pattern.compile("^points$");
	private Pattern pointActual=Pattern.compile("^point:\\((-*[0-9]{1,3}),(-*[0-9]{1,3})\\)$");
	private Pattern pointEnd=Pattern.compile("^endpoints$");
	
	private Pattern sSquiggleStart=Pattern.compile("^Squiggle$");
	private Pattern sSquiggleEnd=Pattern.compile("EndSquiggle");
	
	private Pattern lPolyStart=Pattern.compile("^Polyline$");
	private Pattern lPolyEnd=Pattern.compile("^EndPolyline$");
	
	private Pattern blank=Pattern.compile("^$");
	// ADD MORE!!
	
	/**
	 * Store an appropriate error message in this, including 
	 * lineNumber where the error occurred.
	 * @param mesg
	 */
	private void error(String mesg){
		this.errorMessage = "Error in line "+lineNumber+" "+mesg;
	}
	
	/**
	 * 
	 * @return the error message resulting from an unsuccessful parse
	 */
	public String getErrorMessage(){
		return this.errorMessage;
	}
	
	/**
	 * Parse the inputStream as a Paint Save File Format file.
	 * The result of the parse is stored as an ArrayList of Paint command.
	 * If the parse was not successful, this.errorMessage is appropriately
	 * set, with a useful error message.
	 * 
	 * @param inputStream the open file to parse
	 * @param paintModel the paint model to add the commands to
	 * @return whether the complete file was successfully parsed
	 */
	public boolean parse(BufferedReader inputStream, PaintModel paintModel) {
		System.out.println("______________________________________");
		this.paintModel = paintModel;
		this.errorMessage="";
		
		// During the parse, we will be building one of the 
		// following commands. As we parse the file, we modify 
		// the appropriate command.
		
		CircleCommand circleCommand = null; 
		RectangleCommand rectangleCommand = null;
		SquiggleCommand squiggleCommand = null;
		PolyLineCommand polylineCommand = null;
	
		try {	
			int state=0; Matcher m; String l;
			
			this.lineNumber=0;
			while ((l = inputStream.readLine()) != null) {
				this.lineNumber++;
				l = l.replaceAll("\\s", "");
				System.out.println(lineNumber+" "+l+" "+state);
				switch(state){
					case 0:
						m=pFileStart.matcher(l);
						if(m.matches()){
							state=1;
							break;
						}
						error("Expected Start of Paint Save File");
						return false;
					case 1: // Looking for the start of a new object or end of the save file
						m=pCircleStart.matcher(l);
						if(m.matches()){
							circleCommand = new CircleCommand(null,-1);
							state=2; 
							break;
						}
						m=rRectangleStart.matcher(l);
						if (m.matches()) {
							rectangleCommand = new RectangleCommand(null,null);
							state = 3;
							break;
						}
						m=sSquiggleStart.matcher(l);
						if (m.matches()) {
							squiggleCommand = new SquiggleCommand();
							state = 4;
							break;
						}
						m=lPolyStart.matcher(l);
						if (m.matches()) {
							polylineCommand = new PolyLineCommand();
							state = 5;
							break;
						}
						m=pFileEnd.matcher(l);
						if (m.matches()) {
							state = 66;
							break;
						}
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						else {
							error("expected command start or end file");
							return false;
						}
						
				
					case 2: //color circle
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=pColor.matcher(l);
						if(m.matches()) {
							int r = Integer.parseInt(m.group(1));
							int g = Integer.parseInt(m.group(2));
							int b = Integer.parseInt(m.group(3));
							if(r < 256 && g< 256 && b < 256) {
							Color shapeColor = Color.rgb(r,g,b);
							circleCommand.setColor(shapeColor);
							state = 6;
							break;
							}
							else {
								error("color value out of range");
								return false;
							}
						}else {
							error("expected color");
							return false;
						}
					case 3://color rectangle
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=pColor.matcher(l);
						if(m.matches()) {
							int r = Integer.parseInt(m.group(1));
							int g = Integer.parseInt(m.group(2));
							int b = Integer.parseInt(m.group(3));
							if(r < 256 && g< 256 && b < 256) {
							Color shapeColor = Color.rgb(r,g,b);
							rectangleCommand.setColor(shapeColor);
							state = 7;
							break;
							}
							else {
								error("color value out of range");
								return false;
							}
						}else {
							error("expected color");
							return false;
						}
					case 4://color squiggle
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=pColor.matcher(l);
						if(m.matches()) {
							int r = Integer.parseInt(m.group(1));
							int g = Integer.parseInt(m.group(2));
							int b = Integer.parseInt(m.group(3));
							if(r < 256 && g< 256 && b < 256) {
							Color shapeColor = Color.rgb(r,g,b);
							squiggleCommand.setColor(shapeColor);
							state = 8;
							break;
							}
							else {
								error("color value out of range");
								return false;
							}
						}else {
							error("expected color");
							return false;
						}
					case 5://color polyline
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=pColor.matcher(l);
						if(m.matches()) {
							int r = Integer.parseInt(m.group(1));
							int g = Integer.parseInt(m.group(2));
							int b = Integer.parseInt(m.group(3));
							if(r < 256 && g< 256 && b < 256) {
							Color shapeColor = Color.rgb(r,g,b);
							polylineCommand.setColor(shapeColor);
							state = 9;
							break;
							}
							else {
								error("color value out of range");
								return false;
							}
						}else {
							error("expected color");
							return false;
						}
/////////////////////////////////////////////////////////// start fills
					case 6://fill circle
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=pFilled.matcher(l);
						if(m.matches()) { 
							boolean filled= Boolean.parseBoolean(m.group(1));
							circleCommand.setFill(filled);
							state =10;
							break;
						}else {
							error("expect fill");
							return false;
						}
						
					case 7://fill rectangle
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=pFilled.matcher(l);
						if(m.matches()) { 
							boolean filled= Boolean.parseBoolean(m.group(1));
							rectangleCommand.setFill(filled);
							state =11;
							break;
						}else {
							error("expect fill");
							return false;
						}
					case 8://fill squiggle
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=pFilled.matcher(l);
						if(m.matches()) { 
							boolean filled= Boolean.parseBoolean(m.group(1));
							squiggleCommand.setFill(filled);
							state =12;
							break;
						}else {
							error("expect fill");
							return false;
						}
					case 9://fill polyline
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=pFilled.matcher(l);
						if(m.matches()) { 
							boolean filled= Boolean.parseBoolean(m.group(1));
							polylineCommand.setFill(filled);
							state =13;
							break;
						}else {
							error("expect fill");
							return false;
						}
////////////////////////////////////////////////////////////start attributes 1
					case 10://circle center
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=pCenter.matcher(l);
						if(m.matches()) {
						int centerPoint = Integer.parseInt(m.group(1));
						int centerPoint2 = Integer.parseInt(m.group(2));
						Point centerPointActual = new Point(centerPoint,centerPoint2);
						circleCommand.setCentre(centerPointActual);
						state = 14;
						break;
						}else {
							error("expect circle center");
							return false;
						}
						
					case 11://rectangle p1
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=rP1.matcher(l);
						if(m.matches()) {
							int centerPoint = Integer.parseInt(m.group(1));
							int centerPoint2 = Integer.parseInt(m.group(2));
							Point centerPointActual = new Point(centerPoint,centerPoint2);
							rectangleCommand.setP1(centerPointActual);
							state = 15;
							break;
						}else {
							error("expect rectangle p1");
							return false;
						}
					case 12://squiggle point start - loop
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=pointStart.matcher(l);
						if(m.matches())
							break;
						m = pointActual.matcher(l);
						if(m.matches()) {
							int centerPoint = Integer.parseInt(m.group(1));
							int centerPoint2 = Integer.parseInt(m.group(2));
							Point centerPointActual = new Point(centerPoint,centerPoint2);
							squiggleCommand.add(centerPointActual);
							break;
						}
						m = pointEnd.matcher(l);
						if(m.matches()) {
							state = 16;
							break;
						}
						else {
							error("expected point start, point end or point actual");
						}
					case 13://poly point start - loop
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=pointStart.matcher(l);
						if(m.matches())
							break;
						m = pointActual.matcher(l);
						if(m.matches()) {
							int centerPoint = Integer.parseInt(m.group(1));
							int centerPoint2 = Integer.parseInt(m.group(2));
							Point centerPointActual = new Point(centerPoint,centerPoint2);
							polylineCommand.add(centerPointActual);
							break;
						}
						m = pointEnd.matcher(l);
						if(m.matches()) {
							state = 17;
							break;
						}
						else {
							error("expected point start, point end or point actual");
						}
						
///////////////////////////////////////////////////////////start attributes 2
					case 14://circle radius
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=pRadius.matcher(l);
						if(m.matches()) {
							int centerPoint = Integer.parseInt(m.group(1));
							circleCommand.setRadius(centerPoint);
							state = 18;
							break;
						}
					case 15://rectangle p2
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=rP2.matcher(l);
						if(m.matches()) {
							int centerPoint = Integer.parseInt(m.group(1));
							int centerPoint2 = Integer.parseInt(m.group(2));
							Point centerPointActual = new Point(centerPoint,centerPoint2);
							rectangleCommand.setP2(centerPointActual);
							state = 19;
							break;
						}else {
							error("expect rectangle p2");
							return false;
						}
					case 16://end point squiggle
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m = sSquiggleEnd.matcher(l);
						if(m.matches()) {
							paintModel.addCommand(squiggleCommand);
							state = 1;
							break;
						}
						else {
							error("expected end squiggle");
							return false;
						}
					case 17://end point polyline
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m= lPolyEnd.matcher(l);
						if(m.matches()) {
							paintModel.addCommand(polylineCommand);
							state = 1;
							break;
						}
						else {
							error("expected end polyline");
							return false;
						}
/////////////////////////////////////////////////////////start ends
					case 18://end circle
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=pCircleEnd.matcher(l);
						if(m.matches()) {
							paintModel.addCommand(circleCommand);
							state = 1;
							break;
						} else {
							error("expected end circle");
							return false;
						}
					case 19://end rectangle
						m=blank.matcher(l);
						if (m.matches()) {
							break;
						}
						m=rRectangleEnd.matcher(l);
						if(m.matches()) {
							paintModel.addCommand(rectangleCommand);
							state=1;
							break;
						} else {
							error("expected end rectangle");
							return false;
						}
					case 66://end file case
						error("expected end rectangle");
						return false;
				}
			}
		}  catch (Exception e){
			
		}
		return true;
	}
}
