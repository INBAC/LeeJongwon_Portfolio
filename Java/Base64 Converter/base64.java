/*
Written By: Jongwon
Date: 12/MAR/2019
Purpose: To encode/decode from/to ASCII code to/from base64 code consisting of S={A~Z, a~z, 0~9, +, /}
run example: java base64 inputfile outputfile e (encode ASCII code from inputfile to base64 code to outputfile)
			java base64 inputfile outputfile d (decode base64 code from inputfile to ASCII code to outputfile)
			args[0] = input file
			args[1] = output file
			args[2] = 'e' or 'd' mode for encoding or decoding respectively
*/

import java.util.*;
import java.io.*;
import java.nio.charset.*;
import java.nio.file.*;

public class base64
{
	private static String base64Set;
	private static String input;
	private static String output;
	
	public base64
	{
		base64Set = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
		input = "";
		output = "";
	}

	public static void main(String[] args)
	{
		new base64;
		readFile(args[0]);
		if(args[2].charAt(0) == 'E' || args[2].charAt(0) == 'e')
			encode();
		else if(args[2].charAt(0) == 'D' || args[2].charAt(0) == 'd')
			decode();
		else
			System.out.println("Unknown command");
		writeFile(args[1]);
	}
	
	private static void readFile(String fileName)
	{
		try
		{
			Path inputPath = Paths.get(fileName);
			input = new String(Files.readAllBytes(inputPath), StandardCharsets.US_ASCII);
		}
		catch(IOException e)
		{
			System.out.println("Error reading file");
		}
	}

	private static void encode()
	{
		for(int i = 0; i < input.length(); i++)
		{
			if(i % 3 == 0)
				output += base64Set.charAt(((int)input.charAt(i) & 0xFC) >> 2);
			else if(i % 3 == 1)
				output += base64Set.charAt(((int)input.charAt(i - 1) & 0x03) << 4 | ((int)input.charAt(i) & 0xF0) >> 4);
			else if(i % 3 == 2)
			{
				output += base64Set.charAt(((int)input.charAt(i - 1) & 0x0F) << 2 | ((int)input.charAt(i) & 0xC0) >> 6);
				output += base64Set.charAt((int)input.charAt(i) & 0x3F);
			}
		}
		if(input.length() % 3 == 1)
			output += base64Set.charAt(((int)input.charAt(input.length() - 1) & 0x03) << 4) + "==";
		else if(input.length() % 3 == 2)
			output += base64Set.charAt(((int)input.charAt(input.length() - 1) & 0x0F) << 2) + "=";
	}

	private static void decode()
	{
		for(int i = 0; i < input.length(); i++)
		{
			if(input.charAt(i) != '=')
			{
				if(i % 4 == 1)
					output += (char)(base64Set.indexOf(input.charAt(i - 1)) << 2 | (base64Set.indexOf(input.charAt(i)) & 0x30) >> 4);
				else if(i % 4 == 2)
					output += (char)((base64Set.indexOf(input.charAt(i - 1)) & 0x0F) << 4 | (base64Set.indexOf(input.charAt(i)) & 0x3C) >> 2);
				else if(i % 4 == 3)
					output += (char)((base64Set.indexOf(input.charAt(i - 1)) & 0x03) << 6 | base64Set.indexOf(input.charAt(i)));
			}
		}
	}

	private static void writeFile(String fileName)
	{
		try
		{
			Path outputPath = Paths.get(fileName);
			Files.write(outputPath, output.getBytes(StandardCharsets.US_ASCII));
		}
		catch(IOException erro)
		{
			System.out.println("Error writing file");
		}
	}
}
