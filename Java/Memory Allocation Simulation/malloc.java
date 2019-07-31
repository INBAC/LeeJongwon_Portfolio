/*
	Written By: Jongwon Lee
	Date: 27th MAR 2019
	Purpose: To simulate memory allocation and free with Java.
			I have implemented best fit algorithm with linked list structure.
			I also made it so that the memory is allocated in 1KB block size.
*/

import java.util.*;
import java.io.*;

public class malloc
{
	private static class allocatedAddress
	{
		private int startAddress;
		private int processNum;
		private int endAddress;

		private allocatedAddress(int start, int num, int end)
		{
			this.startAddress = start;
			this.processNum = num;
			this.endAddress = end;
		}
	}
	
	private static class freeAddress
	{
		private int startAddress;
		private int endAddress;
		
		private freeAddress(int start, int end)
		{
			this.startAddress = start;
			this.endAddress = end;
		}
	}
	
	public static final String ANSI_RESET = "\u001B[0m";
	public static final String ANSI_NEW_PAGE = "\033[H\033[2J";
	public static final String ANSI_RED = "\u001B[31m";
	public static final String ANSI_GREEN = "\u001B[32m";
	public static final String ANSI_RED_BACKGROUND = "\u001B[41m";
	public static final String ANSI_GREEN_BACKGROUND = "\u001B[42m";

	private static final int MEMORY_SIZE = 0x100000;
	private static final int MEMORY_BLOCK_SIZE = 0x400;
	private static LinkedList<allocatedAddress> allocatedList;
	private static LinkedList<freeAddress> freeList;
	
	private malloc()
	{
		allocatedList = new LinkedList<>();
		freeList = new LinkedList<>();
		freeList.add(new freeAddress(0, MEMORY_SIZE - 1));
	}
	
	private static void allocate(int processNum, int processSize)
	{
		int i;
		int freeSpace;
		int bestFit = -1;
		int nodePlace = -1;
		if(processSize % MEMORY_BLOCK_SIZE != 0)
			processSize += MEMORY_BLOCK_SIZE - (processSize % MEMORY_BLOCK_SIZE);
		for(i = 0; i < freeList.size(); i++)
		{
			freeSpace = freeList.get(i).endAddress - freeList.get(i).startAddress + 1;
			if(freeSpace >= processSize)
			{
				if(bestFit == -1)
					bestFit = i;
				else if((freeList.get(bestFit).endAddress - freeList.get(bestFit).startAddress + 1) > freeSpace)
					bestFit = i;
			}
		}
		if(bestFit != -1)
		{
			if(allocatedList.size() == 0)
				allocatedList.add(new allocatedAddress(freeList.get(bestFit).startAddress, processNum, freeList.get(bestFit).startAddress + processSize - 1));
			else
			{
				for(i = 0; i < allocatedList.size(); i++)
				{
					if((freeList.get(bestFit).startAddress + processSize - 1) < allocatedList.get(i).startAddress)
					{
						nodePlace = i;
						break;
					}
				}
				if(nodePlace != -1)
					allocatedList.add(nodePlace, new allocatedAddress(freeList.get(bestFit).startAddress, processNum, freeList.get(bestFit).startAddress + processSize - 1));
				else
					allocatedList.addLast(new allocatedAddress(freeList.get(bestFit).startAddress, processNum, freeList.get(bestFit).startAddress + processSize - 1));
			}
			freeList.get(bestFit).startAddress += processSize;
			if(freeList.get(bestFit).startAddress == freeList.get(bestFit).endAddress + 1)
				freeList.remove(bestFit);
		}
	}
	
	private static void free(int process)
	{
		int i;
		int nodePlace = -1;
		int processIndex = -1;
		for(i = 0; i < allocatedList.size(); i++)
			if(allocatedList.get(i).processNum == process)
				processIndex = i;
		if(processIndex != -1)
		{
			if(freeList.size() == 0)
				freeList.add(new freeAddress(allocatedList.get(processIndex).startAddress, allocatedList.get(processIndex).endAddress));
			else
			{
				for(i = 0; i < freeList.size(); i++)
				{
					if(allocatedList.get(processIndex).endAddress < freeList.get(i).startAddress)
					{
						nodePlace = i;
						break;
					}
				}
				if(nodePlace != -1)
					freeList.add(nodePlace, new freeAddress(allocatedList.get(processIndex).startAddress, allocatedList.get(processIndex).endAddress));
				else
					freeList.addLast(new freeAddress(allocatedList.get(processIndex).startAddress, allocatedList.get(processIndex).endAddress));
			}
			allocatedList.remove(processIndex);
			if(freeList.size() > 1)
			{
				for(i = 1; i < freeList.size(); i++)
				{
					if(freeList.get(i - 1).endAddress + 1 == freeList.get(i).startAddress)
					{
						freeList.get(i - 1).endAddress = freeList.get(i).endAddress;
						freeList.remove(i);
						i--;
					}
				}	
			}
		}
	}
	
	private static void printMemory(String input)
	{
		System.out.print(ANSI_NEW_PAGE);
		System.out.println("Memory State");
		int i;
		int printWalk = 0;
		int freeCntr = 0;
		int freeLimit = freeList.size() - 1;
		int allocCntr = 0;
		int allocLimit = allocatedList.size() - 1;
		int blockNum;
		char printMode;
		while(true)
		{
			if(freeLimit > -1 && freeCntr <= freeLimit)
			{
				if(freeList.get(freeCntr).startAddress == printWalk)
				{
					blockNum = (freeList.get(freeCntr).endAddress + 1 - freeList.get(freeCntr).startAddress) / MEMORY_BLOCK_SIZE;
					for(i = 0; i < blockNum; i++)
						System.out.print(ANSI_GREEN + ANSI_GREEN_BACKGROUND + 'O' + ANSI_RESET);
					printWalk = freeList.get(freeCntr).endAddress + 1;
					freeCntr++;
				}
			}
			if(allocLimit > -1 && freeCntr <= freeLimit)
			{
				if(allocatedList.get(allocCntr).startAddress == printWalk)
				{
					blockNum = (allocatedList.get(allocCntr).endAddress + 1 - allocatedList.get(allocCntr).startAddress) / MEMORY_BLOCK_SIZE;
					for(i = 0; i < blockNum; i++)
						System.out.print(ANSI_RED + ANSI_RED_BACKGROUND + 'X' + ANSI_RESET);
					printWalk = allocatedList.get(allocCntr).endAddress + 1;
					allocCntr++;
				}
			}
			if(!(freeLimit > -1 && freeCntr <= freeLimit) && !(allocLimit > -1 && freeCntr <= freeLimit))
				break;
		}
		System.out.println("");
		System.out.println("");
		System.out.println(ANSI_GREEN + "Free Memory List" + ANSI_RESET);
		for(i = 0; i < freeList.size(); i++)
		{
			System.out.print(ANSI_GREEN + "<" + freeList.get(i).startAddress + ", " + freeList.get(i).endAddress + "> " + ANSI_RESET);
		}
		System.out.println("");
		System.out.println("");
		System.out.println(ANSI_RED + "Allocated Memory List" + ANSI_RESET);
		for(i = 0; i < allocatedList.size(); i++)
		{
			System.out.print(ANSI_RED + "<" + allocatedList.get(i).startAddress + ", " + "P:" + allocatedList.get(i).processNum + ", " + allocatedList.get(i).endAddress + "> " + ANSI_RESET);
		}
		System.out.println("");
		System.out.println("");
		System.out.println("Input: " + input);
	}
	
	public static void main(String[] args)
	{
		new malloc();
		int processNum = 0;
		String input = "";
		Scanner scanInput = new Scanner(System.in);
		while(!input.equals("exit"))
		{
			printMemory(input);
			input = scanInput.nextLine();
			if(input.charAt(0) == '+')
				allocate(processNum++, Integer.parseInt(input.substring(3), 16));
			else if(input.charAt(0) == '-')
				free(Integer.parseInt(input.substring(1)));
		}
	}
}
