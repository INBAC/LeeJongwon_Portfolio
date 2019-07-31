import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.Collections;
import java.util.HashMap;
import java.util.LinkedList;

class FileReader
{
    public StringBuffer readFile(String filePath) throws IOException
    {
        return new StringBuffer().append(new String(Files.readAllBytes(Paths.get(filePath))));
    }
}

class Symbol implements Comparable<Symbol>
{
	static HashMap<Character,String> HuffmanTable = new HashMap<Character,String>();
	char ch;
	int frequency;
	String codeword;
	Symbol left;
	Symbol right;
	
	Symbol(char c, int freq)
	{
		ch = c;
		frequency = freq;
		codeword = "";
		left = null;
		right = null;
	}

	@Override
	public int compareTo(Symbol o)
	{
		// TODO Auto-generated method stub
		return this.frequency - o.frequency;
	}
	
	static Symbol buildUp(Symbol cleft, Symbol cright)
	{
		Symbol s = new Symbol((char)0, cleft.frequency + cright.frequency);
		s.left = cleft;
		s.right = cright;
		return s;
	}
	
	public void labeling(String prefix)
	{
		if (this.left != null)
			left.labeling(prefix+"0");
		if (this.right != null)
			right.labeling(prefix+"1");
		if (left == null && right == null)
		{
			this.codeword = prefix;
			HuffmanTable.put(this.ch, this.codeword);
		}
	}
	
	public String toString()
	{
		String str = new String("Symbol info ch: " + this.ch + " freq: " + this.frequency + " codeword: " + this.codeword);
		return str;
	}
}

public class Huffman
{
	public static void main(String[] args) throws IOException
	{
		int[] stat = new int[256];
		FileReader f = new FileReader();
		String inputFile = "src//" + args[0];
		StringBuffer sb = f.readFile(inputFile);
		System.out.println("//////////////////////Original Input Start//////////////////////");
		System.out.println(sb);
		System.out.println("//////////////////////Original Input End//////////////////////\n");
		for (int i = 0 ; i < sb.length(); i++)
		{
			char c = sb.charAt(i);
			int c_ind = (int)c;
			stat[c_ind]++;
		}
		int max = stat[0];
		int max_index = 0;
		for (int i = 1 ; i < 256 ; i++)
		{
			if (max < stat[i])
			{
				max = stat[i];
				max_index = i;
			}
		}
		LinkedList<Symbol> syms = new LinkedList<Symbol>();
		for(int i = 0; i < 256 ; i++)
		{
			Symbol s = new Symbol((char)i, stat[i]);
			syms.add(s);
		}
		Symbol root = null;
		// build up huffman tree
		while(true)
		{
			Collections.sort(syms);
			// pick two least freq nodes
			Symbol s1 = syms.removeFirst();
			if (syms.isEmpty())
			{
				root = s1;
				break;
			}
			Symbol s2 = syms.removeFirst();
			Symbol newnode = Symbol.buildUp(s1, s2);
			syms.add(newnode);
		}
		System.out.println("Input Length: " + root.frequency + " Total Bits Used: " + root.frequency*8);
		System.out.println("The Most Freqent Character: '" + (char)max_index + "'" + " With Frequency Of: " + max);
		root.labeling("");
		StringBuffer strResult = new StringBuffer();
		for (int i = 0 ; i < sb.length(); i++)
		{
			char c = sb.charAt(i);
			strResult.append(Symbol.HuffmanTable.get(Character.valueOf(c)));
		}
		System.out.println("\n//////////////////////Encoded Output Start//////////////////////");
		System.out.println(strResult);
		System.out.println("//////////////////////Encoded Output End//////////////////////\n");
		System.out.println("Compressed Output Bits Used: " + strResult.length());
		System.out.println("Compression Percentage: " + ((double)strResult.length() / ((double)root.frequency*8)) * 100 + "%");
		//decode
		Symbol temp = null;
		StringBuffer decodingOutput = new StringBuffer();
		for(int i = 0; i < strResult.length();)
		{
			temp = root;
			while(temp.left != null && temp.right != null)
			{
					char c = strResult.charAt(i);
					if(Character.valueOf(c) == '0')
						temp = temp.left;
					else if(Character.valueOf(c) == '1')
						temp = temp.right;
					i++;
			}
			decodingOutput.append(temp.ch);
		}
		System.out.println("\n//////////////////////Decoded Output Start//////////////////////");
		System.out.println(decodingOutput);
		System.out.println("//////////////////////Decoded Output End//////////////////////");
	}
}
