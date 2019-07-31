import java.io.*;
import java.security.*;
import java.nio.file.*;

class fileReader
{
    public StringBuffer readFile(String filePath) throws IOException
    {
        return new StringBuffer().append(new String(Files.readAllBytes(Paths.get(filePath))));
    }
}

abstract class DES
{
	final int[][] IPTable = 
	{
		{58, 50, 42, 34, 26, 18, 10, 2},
		{60, 52, 44, 36, 28, 20, 12, 4},
		{62, 54, 46, 38, 30, 22, 14, 6},
		{64, 56, 48, 40, 32, 24, 16, 8},
		{57, 49, 41, 33, 25, 17, 9, 1},
		{59, 51, 43, 35, 27, 19, 11, 3},
		{61, 53, 45, 37, 29, 21, 13, 5},
		{63, 55, 47, 39, 31, 23, 15, 7}
	};

	final int[][] IP_1Table = 
	{
		{40, 8, 48, 16, 56, 24, 64, 32},
		{39, 7, 47, 15, 55, 23, 63, 31},
		{38, 6, 46, 14, 54, 22, 62, 30},
		{37, 5, 45, 13, 53, 21, 61, 29},
		{36, 4, 44, 12, 52, 20, 60, 28},
		{35, 3, 43, 11, 51, 19, 59, 27},
		{34, 2, 42, 10, 50, 18, 58, 26},
		{33, 1, 41, 9, 49, 17, 57, 25}
	};
	
	final int[][][] PC1Table = 
	{
		{
			{57, 49, 41, 33, 25, 17, 9},
			{1, 58, 50, 42, 34, 26, 18},
			{10, 2, 59, 51, 43, 35, 27},
			{19, 11, 3, 60, 52, 44, 36}
		},
	
		{
			{63, 55, 47, 39, 31, 23, 15},
			{7, 62, 54, 46, 38, 30, 22},
			{14, 6, 61, 53, 45, 37, 29},
			{21, 13, 5, 28, 20, 12, 4}
		}
	};
	
	final int[][] PC2Table = 
	{
		{14, 17, 11, 24, 1, 5},
		{3, 28, 15, 6, 21, 10},
		{23, 19, 12, 4, 26, 8},
		{16, 7, 27, 20, 13, 2},
		{41, 52, 31, 37, 47, 55},
		{30, 40, 51, 45, 33, 48},
		{44, 49, 39, 56, 34, 53},
		{46, 42, 50, 36, 29, 32},
	};
	
	final int[][] ETable = 
	{
		{32, 1, 2, 3, 4, 5},
		{4, 5, 6, 7, 8, 9},
		{8, 9, 10, 11, 12, 13},
		{12, 13, 14, 15, 16, 17},
		{16, 17, 18, 19, 20, 21},
		{20, 21, 22, 23, 24, 25},
		{24, 25, 26, 27, 28, 29},
		{28, 29, 30, 31, 32, 1}
	};
	
	final int[][] PTable = 
	{
		{16, 7, 20, 21, 29, 12, 28, 17},
		{1, 15, 23, 26, 5, 18, 31, 10},
		{2, 8, 24, 14, 32, 27, 3, 9},
		{19, 13, 30, 6, 22, 11, 4, 25}
	};
	
	final int[][][] STable = 
	{
		{
			{14, 4, 13, 1, 2, 15, 11, 8, 3, 10, 6, 12, 5, 9, 0, 7},
			{0, 15, 7, 4, 14, 2, 13, 1, 10, 6, 12, 11, 9, 5, 3, 8},
			{4, 1, 14, 8, 13, 6, 2, 11, 15, 12, 9, 7, 3, 10, 5, 0},
			{15, 12, 8, 2, 4, 9, 1, 7, 5, 11, 3, 14, 10, 0, 6, 13}
		},
		
		{
			{15, 1, 8, 14, 6, 11, 3, 4, 9, 7, 2, 13, 12, 0, 5, 10},
			{3, 13, 4, 7, 15, 2, 8, 14, 12, 0, 1, 10, 6, 9, 11, 5},
			{0, 14, 7, 11, 10, 4, 13, 1, 5, 8, 12, 6, 9, 3, 2, 15},
			{13, 8, 10, 1, 3, 15, 4, 2, 11, 6, 7, 12, 0, 5, 14, 9}
		},
		
		{
			{10, 0, 9, 14, 6, 3, 15, 5, 1, 13, 12, 7, 11, 4, 2, 8},
			{13, 7, 0, 9, 3, 4, 6, 10, 2, 8, 5, 14, 12, 11, 15, 1},
			{13, 6, 4, 9, 8, 15, 3, 0, 11, 1, 2, 12, 5, 10, 14, 7},
			{1, 10, 13, 0, 6, 9, 8, 7, 4, 15, 14, 3, 11, 5, 2, 12}
		},
		
		{
			{7, 13, 14, 3, 0, 6, 9, 10, 1, 2, 8, 5, 11, 12, 4, 15},
			{13, 8, 11, 5, 6, 15, 0, 3, 4, 7, 2, 12, 1, 10, 14, 9},
			{10, 6, 9, 0, 12, 11, 7, 13, 15, 1, 3, 14, 5, 2, 8, 4},
			{3, 15, 0, 6, 10, 1, 13, 8, 9, 4, 5, 11, 12, 7, 2, 14}
		},
		
		{
			{2, 12, 4, 1, 7, 10, 11, 6, 8, 5, 3, 15, 13, 0, 14, 9},
			{14, 11, 2, 12, 4, 7, 13, 1, 5, 0, 15, 10, 3, 9, 8, 6},
			{4, 2, 1, 11, 10, 13, 7, 8, 15, 9, 12, 5, 6, 3, 0, 14},
			{11, 8, 12, 7, 1, 14, 2, 13, 6, 15, 0, 9, 10, 4, 5, 3}
		},
		
		{
			{12, 1, 10, 15, 9, 2, 6, 8, 0, 13, 3, 4, 14, 7, 5, 11},
			{10, 15, 4, 2, 7, 12, 9, 5, 6, 1, 13, 14, 0, 11, 3, 8},
			{9, 14, 15, 5, 2, 8, 12, 3, 7, 0, 4, 10, 1, 13, 11, 6},
			{4, 3, 2, 12, 9, 5, 15, 10, 11, 14, 1, 7, 6, 0, 8, 13}
		},
		
		{
			{4, 11, 2, 14, 15, 0, 8, 13, 3, 12, 9, 7, 5, 10, 6, 1},
			{13, 0, 11, 7, 4, 9, 1, 10, 14, 3, 5, 12, 2, 15, 8, 6},
			{1, 4, 11, 13, 12, 3, 7, 14, 10, 15, 6, 8, 0, 5, 9, 2},
			{6, 11, 13, 8, 1, 4, 10, 7, 9, 5, 0, 15, 14, 2, 3, 12}
		},
		
		{
			{13, 2, 8, 4, 6, 15, 11, 1, 10, 9, 3, 14, 5, 0, 12, 7},
			{1, 15, 13, 8, 10, 3, 7, 4, 12, 5, 6, 11, 0, 14, 9, 2},
			{7, 11, 4, 1, 9, 12, 14, 2, 0, 6, 10, 13, 15, 3, 5, 8},
			{2, 1, 14, 7, 4, 10, 8, 13, 15, 12, 9, 0, 3, 5, 6, 11}
		}
	};

	long P_box(long input, int[][] table, int inputSize)
	{
		long output = 0;
		for(int i = 0; i < table.length; i++)
			for(int j = 0; j < table[i].length; j++)
				output = (output << 1) | ((input  >>> (inputSize - table[i][j])) & 0x1L);
		return output;
	}
	
	long roundShift(long input, int roundNumber)
	{
		int shiftNumber = (roundNumber == 0 || roundNumber == 1 || roundNumber == 8 || roundNumber == 15) ? 1 : 2;
		input = ((input >>> (28 - shiftNumber)) | (input << shiftNumber)) & 0xFFF_FFFFL;
		return input;
	}
	
	long[] generateSubkey(long key)
	{
		long[] subkey = new long[16];
		long left = P_box(key, this.PC1Table[0], 64);
		long right = P_box(key, this.PC1Table[1], 64);
		long temp = 0;
		for(int i = 0; i < 16; i++)
		{
			left = roundShift(left, i);
			right = roundShift(right, i);
			temp = (left << 28) | right;
			subkey[i] = P_box(temp, this.PC2Table, 56);
		}
		return subkey;
	}
	
	long S_box(long input, int[][] table)
	{
		long output = 0;
		long[] coordinate = new long[2];
		coordinate[0] = ((input & 0x20L) >>> 4) | (input & 1L);
		coordinate[1] = (input & 0x1EL) >>> 1;
		output = table[(int)coordinate[0]][(int)coordinate[1]];
		return output;
	}
	
	long F_box(long input, long subkey)
	{
		long output = 0;
		input = P_box(input, this.ETable, 32);
		input ^= subkey;
		for(int i = 0; i < 8; i++)
		{
 			output = output << 4;
 			output |= S_box((input >>> (42 - (i * 6))) & 0x3FL, STable[i]);
		}
		output = P_box(output, this.PTable, 32);
		return output;
	}
}

class DES_encryptor extends DES
{	
	long encript(long block, long key)
	{
		long cipher = 0;
		block = P_box(block, this.IPTable, 64);
		long left = block >>> 32;
		long right = block & 0xFFFF_FFFFL;
		long temp = 0;
		long[] subkey = generateSubkey(key);
		for(int i = 0; i < 16; i++)
		{
			temp = left;
			left = right;
			right = temp ^ F_box(right, subkey[i]);
		}
		block = (right << 32) | left;
		cipher = P_box(block, this.IP_1Table, 64);
		return cipher;
	}
}

class DES_decryptor extends DES
{
	long decrypt(long cipher, long key)
	{
		long block = 0;
		cipher = P_box(cipher, this.IPTable, 64);
		long left = cipher >>> 32;
		long right = cipher & 0xFFFF_FFFFL;
		long temp = 0;
		long[] subkey = generateSubkey(key);
		for(int i = 0; i < 16; i++)
		{
			temp = left;
			left = right;
			right = temp ^ F_box(right, subkey[15 - i]);
		}
		cipher = (right << 32) | left;
		block = P_box(cipher, this.IP_1Table, 64);
		return block;
	}
}

public class DES_encryption
{
	public static void main(String[] args)throws IOException
	{
		DES_encryptor encryptingObject = new DES_encryptor();
		DES_decryptor decryptingObject = new DES_decryptor();
		long key = new SecureRandom().nextLong();
		System.out.println(String.format("The Key is: 0x%016X", key));
		StringBuffer input = new fileReader().readFile("src//" + args[0]);
		System.out.println("\n\n#########################################Input in Byte#########################################");
		for(int i = 0; i < input.length(); i++)
			System.out.print(String.format("%02X ", (byte)input.charAt(i)));
		System.out.println("\n\n#########################################Input in Text#########################################");
		System.out.println(input + "\n");
		int blockNumber = (input.length() + 7) / 8;
		long[] block = new long[blockNumber];
		long[] cipher = new long[blockNumber];
		long[] decryption = new long[blockNumber];
		int byteNumber = blockNumber * 8;
		byte[] cipherByte = new byte[byteNumber];
		byte[] decryptionByte = new byte[byteNumber];
		for(int i = 0; i < block.length; i++)
			for(int j = 0; j < 8; j++)
			{
				block[i] = block[i] << 8;
				if(input.length() > (i * 8) + j)
					block[i] |= (long)input.charAt((i * 8) + j);
			}
		System.out.println("\n\n#########################################Cipher in Byte#########################################");
		for(int i = 0; i < block.length; i++)
		{
			cipher[i] = encryptingObject.encript(block[i], key);
			for(int j = 0; j < 8; j++)
			{
				cipherByte[(i * 8) + j] = (byte)((cipher[i] >>> (56 - j * 8)) & 0xFF);
				System.out.print(String.format("%02X ", cipherByte[(i * 8) + j]));
			}
		}
		System.out.println("\n\n#########################################Cipher in Text#########################################");
		System.out.println(new String(cipherByte) + "\n");
		System.out.println("\n\n########################################Decipher in Byte########################################");
		for(int i = 0; i < cipher.length; i++)
		{
			decryption[i] = decryptingObject.decrypt(cipher[i], key);
			for(int j = 0; j < 8; j++)
			{
				decryptionByte[(i * 8) + j] = (byte)((decryption[i] >>> (56 - j * 8)) & 0xFF);
				System.out.print(String.format("%02X ", decryptionByte[(i * 8) + j]));
			}
		}
		System.out.println("\n\n########################################Decipher in Text########################################");
		System.out.println(new String(decryptionByte));
	}
}
