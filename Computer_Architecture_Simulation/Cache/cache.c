#include<stdio.h>
#include<stdlib.h>
#include<string.h>


//Define-----------------------------------------------------------------------


enum R_type
{
	add = 0x20,//Add
	addu = 0x21,//Add Unsigned
	and = 0x24,//And
	jr = 0x08,//Jump Register
	nor = 0x27,//Nor
	or = 0x25,//Or
	slt = 0x2a,//Set Less Than
	sltu = 0x2b,//Set Less Than Unsigned
	sll = 0x00,//Shift Left Logical
	srl = 0x02,//Shift Right Logical
	sub = 0x22,//Subtract
	subu = 0x23//Subtract Unsigned
};

enum I_type
{
	addi = 0x8,//Add Immediate
	addiu = 0x9,//Add Immediate Unsigned
	andi = 0xc,//And Immediate
	beq = 0x4,//Brandch On Equal
	bne = 0x5,//Brandch On Not Equal
	lbu = 0x24,//Load Byte Unsigned
	lhu = 0x25,//Load Halfword Unsigned
	ll = 0x30,//Load Linked
	lui = 0xf,//Load Upper Immediate
	lw = 0x23,//Load Word
	ori = 0xd,//Or Immediate
	slti = 0xa,//Set Less Than Immediate
	sltiu = 0xb,//Set Less Than Immediate Unsigned
	sb = 0x28,//Store Byte
	sc = 0x38,//Store Conditional
	sh = 0x29,//Store Halfword
	sw = 0x2b//Store Word
};

enum J_type
{
	j = 0x2,//Jump
	jal = 0x3//Jump And Link
};

typedef struct
{
	int PC;
	int instruction;
}IFID;

typedef struct
{
	int PC;
	int type;
	int val1;
	int val2;

	//Control Signal
	int RegDst;
	int Jump;
	int Branch;
	int MemRead;
	int MemtoReg;
	int ALUOp;
	int MemWrite;
	int ALUSrc;
	int RegWrite;
	int JumpReg;

	//value
	int opcode;
	int rs;
	int rt;
	int rd;
	int shamt;
	int funct;
	int immediate;
	int address;
	int SignExtImm;
	int ZeroExtImm;
	int BranchAddr;
	int JumpAddr;
}IDEX;

typedef struct
{
	int PC;

	//Control Signal
	int Jump;
	int Branch;
	int MemRead;
	int MemtoReg;
	int MemWrite;
	int JumpReg;
	int RegWrite;

	//value
	int result;
	int writeData;
	int regAddr;
}EXMEM;

typedef struct
{
	int PC;
	int MemtoReg;
	int RegWrite;
	int regAddr;
	int readData;
	int result;
}MEMWB;

typedef struct
{
	unsigned int tag;
	int data[16];
	unsigned int sca;
	unsigned int dirty;
	unsigned int valid;
	unsigned int old;
}CACHELINE;

typedef struct
{
	CACHELINE cacheLine[32];
}CACHE;

//Declare variable-------------------------------------------------------------
int memory[0x100000];
int reg[32] = {0};
int cycle = 1;
int PC = 0;
IFID IF;
IFID IDin;
IDEX IDout;
IDEX EXin;
EXMEM EXout;
EXMEM MEMin;
MEMWB MEMout;
MEMWB WB;

CACHE cache[4];
int hitCount;
int missCount;
int oldest[32] = {0};

int instructionNum;
int memoryOpsNum;
int regOpsNum;
int branchTakenNum;
int branchNTakenNum;
int jumpNum;

//Declare funcion-------------------------------------------------------------
void getInstructions(FILE *fptr);
void initialize(void);

void fetch(void);
void decode(void);
void execute(void);
void memoryAccess(void);
void writeBack(void);
void latchUpdate(void);

void setControlSig(void);
int MUX (int input1, int input2, int select);

int readMemory(int addr);
void writeMemory(int addr, int value);

int readCache(int addr);
void writeCache(int addr, int value);

int getTag(int addr);
int getOffset(int addr);
int getIndex(int addr);
int getBase(int addr);
//Main-------------------------------------------------------------------------
void main (int argc, char *argv[])
{
	FILE *fptr = NULL;
	if(argc == 1)
	{
		printf("Missing File\n");
		return;
	}
	else if(argc == 2)
	{
		fptr = fopen(argv[1], "rb");
		if(fptr == NULL)
		{
			printf("Could not open file\n");
			return;
		}
	}
	else
	{
		printf("Too Many Arguments\n");
		return;
	}
	getInstructions(fptr);
	initialize();
//the main loop
	while(WB.PC != 0xFFFFFFFF)
	{
		printf("=======Cycle:%d==PC:%X===============\n",cycle, PC);
		fetch();
		writeBack();
		decode();
		execute();
		memoryAccess();
		latchUpdate();
		printf("(r[0]:%d r[2]:%d r[3]:%d r[29]:%d r[30]:%d)\n\n",reg[0], reg[2], reg[3], reg[29], reg[30]);
	}
	printf("=============================================\nNumber of Instructions:%d\nNumber of Memory Operations:%d\nNumber of Register Operations:%d\nNumber of Branch Taken:%d\nNumber of Branch Not Taken:%d\nNumber of Jumps%d\nNumber of Cycles:%d\nFinal R[2]:%d\nNumber of Cache Hit:%d\nNumber of Cache Miss:%d\n", instructionNum, memoryOpsNum, regOpsNum, branchTakenNum, branchNTakenNum, jumpNum, cycle, reg[2], hitCount, missCount);
	fclose(fptr);
	return;
}

//Define function--------------------------------------------------------------
void getInstructions(FILE *fptr)
{
	int i = 0;
	int readbuffer = 0;
	while(fread(&readbuffer, sizeof(readbuffer), 1, fptr) != 0)
	{
		memory[i] = (readbuffer & 0xFF) << 24
				| (readbuffer & 0xFF00) << 8
				| (readbuffer & 0xFF0000) >> 8
				| (readbuffer & 0xFF000000) >> 24;
		printf("%X\n", memory[i]);
		i++;
	}
	return;
}

void initialize(void)
{
	reg[29] = 0x400000;
	reg[31] = 0xFFFFFFFF;
	memset(&IF, 0, sizeof(IFID));
	memset(&IDin, 0, sizeof(IFID));
	memset(&IDout, 0, sizeof(IDEX));
	memset(&EXin, 0, sizeof(IDEX));
	memset(&EXout, 0, sizeof(EXMEM));
	memset(&MEMin, 0, sizeof(EXMEM));
	memset(&MEMout, 0, sizeof(MEMWB));
	memset(&WB, 0, sizeof(MEMWB));
	memset(&cache, 0, sizeof(cache));
	instructionNum = 0;
	memoryOpsNum = 0;
	regOpsNum = 0;
	branchTakenNum = 0;
	branchNTakenNum = 0;
	jumpNum = 0;
	return;
}

void fetch(void)
{
	IF.instruction = memory[PC / 4];
	IF.PC = PC;
	printf("-----FETCH-PC:%X-----\n", IF.PC);
	PC = PC + 4;
	printf("Instruction:%X\n", IF.instruction);
	printf("\n");
	instructionNum++;
	return;
}

void decode(void)
{
	printf("-----DECODE-PC:%X-----\n",IDin.PC);
	IDout.opcode = (IDin.instruction & 0xFC000000) >> 26;
	if(IDout.opcode == 0)
	{
		IDout.type = 0;
		IDout.rs = (IDin.instruction & 0x03E00000) >> 21;
		IDout.rt = (IDin.instruction & 0x001F0000) >> 16;
		IDout.rd = (IDin.instruction & 0x0000F800) >> 11;
		IDout.shamt = (IDin.instruction & 0x000007C0) >> 6;
		IDout.funct = IDin.instruction & 0x0000003F;
		IDout.val1 = reg[IDout.rs];
		IDout.val2 = reg[IDout.rt];
		printf("opcode:%X rs:%X rt:%X rd:%X shamt:%d funct:%X val1:%d val2:%d\n", IDout.opcode, IDout.rs, IDout.rt, IDout.rd, IDout.shamt, IDout.funct, IDout.val1, IDout.val2);
	}
	else if(IDout.opcode == j || IDout.opcode == jal)
	{
		IDout.type = 2;
		IDout.address = IDin.instruction & 0x03FFFFFF;
		IDout.JumpAddr = (IDin.PC & 0xF0000000) | (IDout.address << 2);
		printf("address:%X JumpAddr:%X\n", IDout.address, IDout.JumpAddr);
	}
	else
	{
		IDout.type = 1;
		IDout.rs = (IDin.instruction & 0x03E00000) >> 21;
		IDout.rt = (IDin.instruction & 0x001F0000) >> 16;
		IDout.immediate = IDin.instruction & 0x0000FFFF;
		IDout.SignExtImm = (short)IDout.immediate;
		IDout.ZeroExtImm = IDout.immediate;
		IDout.BranchAddr = IDout.SignExtImm << 2;
		IDout.val1 = reg[IDout.rs];
		IDout.val2 = reg[IDout.rt];
		printf("opcode:%X rs%X rt:%X immediate:%X SignExtImm:%X BranchAddr:%X val1:%d val2:%d\n", IDout.opcode, IDout.rs, IDout.rt, IDout.immediate, IDout.SignExtImm, IDout.BranchAddr, IDout.val1, IDout.val2);
	}
	if(IDout.type == 2)
	{
		IDout.ALUOp = IDout.opcode;
		switch(IDout.ALUOp)
		{
			case j:
				PC = IDout.JumpAddr;
				jumpNum++;
				break;

			case jal:
				reg[31] = IDin.PC + 8;
				PC = IDout.JumpAddr;
				jumpNum++;
				break;

			default:
				break;
		}
	}
	setControlSig();
	printf("RegDst %d\nALUSrc %d\nMemtoReg %d\nRegWrite %d\nMemRead %d\nMemWrite %d\nJump %d\nBranch%d\n", IDout.RegDst, IDout.ALUSrc, IDout.MemtoReg, IDout.RegWrite, IDout.MemRead, IDout.MemWrite, IDout.Jump, IDout.Branch);
	IDout.PC = IDin.PC;
	printf("\n");
	return;
}

void execute(void)
{
	int ALUv2 = 0;
	printf("-----EXECUTE-PC:%X-----\n", EXin.PC);
//Data Dependency==============================================================
	if((EXin.rs != 0)
	&& (EXin.rs == WB.regAddr)
	&& (WB.RegWrite == 1))
	{
		EXin.val1 = MUX(WB.result, WB.readData, WB.MemtoReg);
		printf("Dst2 Val1 Data Dependency\n");
	}

	if((EXin.rs != 0)
	&& (EXin.rs == MEMin.regAddr)
	&& (MEMin.RegWrite == 1))
	{
		EXin.val1 = MEMin.result;
		printf("Dst1 Val1 Data Dependency\n");
	}

	if((EXin.rt != 0)
	&& (EXin.rt == WB.regAddr)
	&& (WB.RegWrite == 1))
	{
		EXin.val2 = MUX(WB.result, WB.readData, WB.MemtoReg);
		printf("Dst2 Val2 Data Dependency\n");
	}

	if((EXin.rt != 0)
	&& (EXin.rt == MEMin.regAddr)
	&& (MEMin.RegWrite == 1))
	{
		EXin.val2 = MEMin.result;
		printf("Dst1 Val2 Data Dependency\n");
	}
//=============================================================================
	ALUv2 = MUX(EXin.val2, EXin.SignExtImm, EXin.ALUSrc);
	if(EXin.type == 0)
	{
		EXin.ALUOp = EXin.funct;
		switch(EXin.ALUOp)
		{
			case add:
				EXout.result = EXin.val1 + ALUv2;
				printf("add r[%d] = %d\n",EXin.rd, EXout.result);
				break;

			case addu:
				EXout.result = EXin.val1 + ALUv2;
				printf("addu r[%d] = %d\n",EXin.rd, EXout.result);
				break;

			case and:
				EXout.result = EXin.val1 & ALUv2;
				printf("and r[%d] = %d\n",EXin.rd, EXout.result);
				break;

			case jr:
				PC = EXin.val1;
				EXin.PC = EXin.val1;
				break;

			case nor:
				EXout.result = ~(EXin.val1 | ALUv2);
				printf("nor r[%d] = %d\n",EXin.rd, EXout.result);
				break;

			case or:
				EXout.result = EXin.val1 | ALUv2;
				printf("or r[%d] = %d\n",EXin.rd, EXout.result);
				break;

			case slt:
				EXout.result = (EXin.val1 < ALUv2)? 1 : 0;
				printf("slt r[%d] = %d\n",EXin.rd, EXout.result);
				break;

			case sltu:
				EXout.result = (EXin.val1 < ALUv2)? 1 : 0;
				printf("sltu r[%d] = %d\n",EXin.rd, EXout.result);
				break;

			case sll:
				EXout.result = EXin.val2 << EXin.shamt;
				printf("sll r[%d] = %d\n",EXin.rd, EXout.result);
				break;

			case srl:
				EXout.result = EXin.val2 >> EXin.shamt;
				printf("srl r[%d] = %d\n",EXin.rd, EXout.result);
				break;

			case sub:
				EXout.result = EXin.val1 - ALUv2;
				printf("sub r[%d] = %d\n",EXin.rd, EXout.result);
				break;

			case subu:
				EXout.result = EXin.val1 - ALUv2;
				printf("subu r[%d] = %d\n",EXin.rd, EXout.result);
				break;

			default:
				break;
		}
	}
	else if(EXin.type == 1)
	{
		EXin.ALUOp = EXin.opcode;
		switch(EXin.ALUOp)
		{
			case addi:
				EXout.result = EXin.val1 + ALUv2;
				printf("addi r[%d] = %d\n",EXin.rt, EXout.result);
				break;

			case addiu:
				EXout.result = EXin.val1 + ALUv2;
				printf("addiu r[%d] = %d\n",EXin.rt, EXout.result);
				break;

			case andi:
				EXout.result = EXin.val1 & ALUv2;
				printf("andi r[%d] = %d\n",EXin.rt, EXout.result);
				break;

			case beq:
				if(EXin.val1 == EXin.val2)
				{
					memset(&IF, 0, sizeof(IFID));
					memset(&IDout,0, sizeof(IDEX));
					PC = EXin.PC + 4 + EXin.BranchAddr;
					EXout.result = 1;
					printf("BEQ Taken\n");
					branchTakenNum++;
				}
				else
				{
					printf("BEQ Not Taken\n");
					EXout.result = 0;
					branchNTakenNum++;
				}
				
				break;

			case bne:
				if(EXin.val1 != EXin.val2)
				{
					memset(&IF, 0, sizeof(IFID));
					memset(&IDout, 0, sizeof(IDEX));
					PC = EXin.PC + 4 + EXin.BranchAddr;
					EXout.result = 1;
					printf("BNE Taken\n");
					branchTakenNum++;
				}
				else
				{
					printf("BNE Not Taken\n");
					EXout.result = 0;
					branchNTakenNum++;
				}
				break;

			case lw:
				EXout.result = EXin.val1 + ALUv2;
				break;

			case ori:
				EXout.result = EXin.val1 | EXin.ZeroExtImm;
				printf("ori r[%d] = %d\n",EXin.rt, EXout.result);
				break;

			case slti:
				EXout.result = (EXin.val1 < ALUv2)? 1 : 0;
				printf("slti r[%d] = %d\n",EXin.rt, EXout.result);
				break;

			case sltiu:
				EXout.result = (EXin.val1 < ALUv2)? 1 : 0;
				printf("sltiu r[%d] = %d\n",EXin.rt, EXout.result);
				break;

			case sw:
				EXout.result = EXin.val1 + ALUv2;
				EXout.writeData = EXin.val2;
				break;

			case lui:
				EXout.result = EXin.SignExtImm << 16;
				break;

			default:
				break;
		}
	}

	EXout.regAddr = MUX(EXin.rt, EXin.rd, EXin.RegDst);

	EXout.PC = EXin.PC;

	EXout.MemRead = EXin.MemRead;
	EXout.MemtoReg = EXin.MemtoReg;
	EXout.MemWrite = EXin.MemWrite;
	EXout.JumpReg = EXin.JumpReg;
	EXout.RegWrite = EXin.RegWrite;

	return;
}

void memoryAccess(void)
{
	printf("-----MEMORYACCESS-PC:%X-----\n", MEMin.PC);
	if(MEMin.MemWrite == 1)
	{
		writeCache(MEMin.result, MEMin.writeData);
		printf("sw memory[%X] = %d\n", MEMin.result, MEMin.writeData);
		memoryOpsNum++;
	}
	if(MEMin.MemRead == 1)
	{
		MEMout.readData = readCache(MEMin.result);
		printf("lw memory[%X] = %d\n", MEMin.result, MEMout.readData);
		memoryOpsNum++;
	}
	MEMout.PC = MEMin.PC;

	MEMout.result = MEMin.result;
	MEMout.regAddr = MEMin.regAddr;
	MEMout.MemtoReg = MEMin.MemtoReg;
	MEMout.RegWrite = MEMin.RegWrite;
	printf("\n");
	return;
}

void writeBack(void)
{
	printf("-----WRITEBACK-PC:%X-----\n", WB.PC);
	if(WB.RegWrite == 1)
	{
		reg[WB.regAddr] = MUX(WB.result, WB.readData, WB.MemtoReg);
		printf("r[%d] = %d\n", WB.regAddr, reg[WB.regAddr]);
		regOpsNum++;
	}
	printf("\n");
	return;
}

void latchUpdate(void)
{
	memset(&IDin, 0, sizeof(IFID));
	memset(&EXin, 0, sizeof(IDEX));
	memset(&MEMin, 0, sizeof(EXMEM));
	memset(&WB, 0, sizeof(MEMWB));

	IDin = IF;
	EXin = IDout;
	MEMin = EXout;
	WB = MEMout;
	cycle++;
	return;
}

int MUX (int input1, int input2, int select)
{
	return select == 0 ? input1 : input2;
}

void setControlSig (void)
{
	IDout.RegDst = 0;
	IDout.Jump = 0;
	IDout.Branch = 0;
	IDout.MemRead = 0;
	IDout.MemtoReg = 0;
	IDout.ALUOp = 0;
	IDout.MemWrite = 0;
	IDout.ALUSrc = 0;
	IDout.RegWrite = 0;
	IDout.JumpReg = 0;

	if(IDout.type == 0)
	{
		IDout.RegDst = 1;
		if(IDout.funct == jr)
		{
			IDout.JumpReg = 1;
		}
		else
		{
			IDout.RegWrite = 1;
		}
	}
	else if(IDout.type == 1)
	{
		IDout.ALUSrc = 1;
		IDout.RegWrite = 1;
		if(IDout.opcode == beq
		|| IDout.opcode == bne)
		{
			IDout.Branch = 1;
			IDout.ALUSrc = 0;
			IDout.RegWrite = 0;
		}

		else if(IDout.opcode == lw
			|| IDout.opcode == ll
			|| IDout.opcode == lhu
			|| IDout.opcode == lbu)
		{
			IDout.MemRead = 1;
			IDout.MemtoReg = 1;
		}

		else if(IDout.opcode == sb
			|| IDout.opcode == sc
			|| IDout.opcode == sh
			|| IDout.opcode == sw)
		{
			IDout.MemWrite = 1;
			IDout.RegWrite = 0;
		}
	}
	else if(IDout.type == 2)
	{
		IDout.Jump = 1;
	}
	return;
}

int readMemory(int addr)
{
	return memory[addr];
}

void writeMemroy(int addr, int value)
{
	memory[addr] = value;
	return;
}

int readCache(int addr)
{
	unsigned int index = getIndex(addr);
	unsigned int base = getBase(addr);
	unsigned int tag = getTag(addr);
	unsigned int offset = getOffset(addr);
	int i;
	int j;
	int t;
	for (i = 0; i < 4; i++)
	{
		if(cache[i].cacheLine[index].valid == 1
		&& cache[i].cacheLine[index].tag == tag)
		{
			cache[i].cacheLine[index].sca = 1;
			hitCount++;
			cache[i].cacheLine[index].old = hitCount + missCount;
			return cache[i].cacheLine[index].data[offset / 4];
		}
		else if(cache[i].cacheLine[index].valid == 0)
		{
			missCount++;
			break;
		}
	}
	if(i == 4)
	{
		missCount++;
		oldest[index] = 0;
		for(j = 1; j < 4; j++)
		{
			if(cache[j].cacheLine[index].old < cache[oldest[index]].cacheLine[index].old)
			{
				oldest[index] = j;
			}
		}
		i = oldest[index];
		for(j = i; ;j++)
		{
			j = j % 4;
			if(cache[j].cacheLine[index].sca == 0)
			{
				if(cache[j].cacheLine[index].dirty == 0)
				{
					i = j;
					break;
				}
				else
				{
					for(t = 0; t < 16; t++)
					{
						memory[((cache[j].cacheLine[index].tag | (index << 6)) / 4) + t] = cache[j].cacheLine[index].data[t];
					}
					cache[j].cacheLine[index].dirty = 0;
					i = j;
					break;
				}
			}
			else
			{
				cache[j].cacheLine[index].sca = 0;
			}
		}
	}
	cache[i].cacheLine[index].tag = tag;
	cache[i].cacheLine[index].old = hitCount + missCount;
	cache[i].cacheLine[index].valid = 1;
	for(t = 0; t < 16; t++)
	{
		cache[i].cacheLine[index].data[t] = memory[(base / 4) + t];
	}
	return cache[i].cacheLine[index].data[offset / 4];
}

void writeCache(int addr, int value)
{
	unsigned int index = getIndex(addr);
	unsigned int base = getBase(addr);
	unsigned int tag = getTag(addr);
	unsigned int offset = getOffset(addr);
	int i;
	int j;
	int t;

	for(j = 0; j < 4; j++)
	{
		if(cache[j].cacheLine[index].valid == 0)
		{
			cache[j].cacheLine[index].valid = 1;
			cache[j].cacheLine[index].tag = tag;
			cache[j].cacheLine[index].data[offset / 4] = value;
			cache[j].cacheLine[index].old = hitCount + missCount;
			cache[j].cacheLine[index].dirty = 1;
			break;
		}
		else
		{
			if(cache[j].cacheLine[index].tag == tag)
			{
				if(cache[j].cacheLine[index].dirty == 0)
				{
					cache[j].cacheLine[index].data[offset / 4] = value;
					cache[j].cacheLine[index].dirty = 1;
					break;
				}
				else
				{
					for(t = 0; t < 16; t++)
					{
						memory[((cache[j].cacheLine[index].tag | (index << 6)) / 4) + t] = cache[j].cacheLine[index].data[t];
					}
					cache[j].cacheLine[index].data[offset / 4] = value;
					break;
				}
			}
		}
	}
	if(j == 4)
	{
		oldest[index] = 0;
		for(j = 1; j < 4; j++)
		{
			if(cache[j].cacheLine[index].old < cache[oldest[index]].cacheLine[index].old)
			{
				oldest[index] = j;
			}
		}
		i = oldest[index];
		for(j = i; ; j++)
		{
			j = j % 4;
			if(cache[j].cacheLine[index].sca == 0)
			{
				if(cache[j].cacheLine[index].dirty == 0)
				{
					cache[j].cacheLine[index].dirty = 1;
				}
				else
				{
					for(t = 0; t <16; t++)
					{
						 memory[((cache[j].cacheLine[index].tag | (index << 6)) / 4) + t] = cache[j].cacheLine[index].data[t];
					}
					cache[j].cacheLine[index].dirty = 0;
				}
				cache[j].cacheLine[index].tag = tag;
				for(t = 0; t < 16; t++)
				{
					cache[j].cacheLine[index].data[t] = memory[(base / 4) + t];
				}
				cache[j].cacheLine[index].data[offset / 4] = value;
				cache[j].cacheLine[index].old = missCount + hitCount;
				break;
			}
			else
			{
				cache[j].cacheLine[index].sca = 0;
			}
		}
	}
	return;
}

int getTag(int addr)
{
	return addr & 0xFFFFF800;
}

int getOffset(int addr)
{
	return addr & 0x0000003F;
}

int getIndex(int addr)
{
	return (addr & 0x000007C0) >> 6;
}

int getBase(int addr)
{
	return addr & 0xFFFFFFC0;
}
