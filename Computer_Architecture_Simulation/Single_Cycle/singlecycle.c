#include<stdio.h>
#include<stdlib.h>
#include<string.h>

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

int memory[0x800000];
int PC = 0;
int r[32] = { 0 };
unsigned int instruction = 0;
unsigned int opcode = 0;
unsigned int rs = 0;
unsigned int rt = 0;
unsigned int rd = 0;
unsigned int shamt = 0;
unsigned int funct = 0;
int immediate = 0;
unsigned int address = 0;
int buffer = 0;
unsigned int JumpAddr = 0;
unsigned int BranchAddr = 0;
int SignExtImm = 0;
int ZeroExtImm = 0;
int R_cntr = 0;
int I_cntr = 0;
int J_cntr = 0;
int branch_cntr = 0;
char type='0';


void fetch(void)
{
        instruction = memory[PC / 4];
        return;
}

void decode(void)
{
        opcode = (instruction & 0xFC000000) >> 26;
	printf("opcode:%X\t", opcode);
        if(opcode == 0)
        {
		rs = (instruction & 0x03E00000) >> 21;
	        rt = (instruction & 0x001F0000) >> 16;
		rd = (instruction & 0x0000F800) >> 11;
		shamt = (instruction & 0x000007C0) >> 6;
	        funct = instruction & 0x0000003F;
		type = 'R';
		printf("rs:%d\trt:%d\trd:%d\tshamt:%d\tfunct:%d\n", rs, rt, rd, shamt, funct);
        }
        else if(opcode == j || opcode == jal)
	{
	        address = instruction & 0x03FFFFFF;
		printf("address:%x\n", address);
		type = 'J';
	}
	else
        {
                rs = (instruction & 0x03E00000) >> 21;
                rt = (instruction & 0x001F0000) >>16;
                immediate = instruction & 0x0000FFFF;
		type = 'I';
                printf("rs:%d\trt:%d\timmediate:%d\n", rs, rt, immediate);
        }

	 return;
}

void execute(void)
{
        JumpAddr = PC & 0xF0000000 | address << 2;
        SignExtImm = (short)immediate;
        BranchAddr = SignExtImm << 2;
	ZeroExtImm = immediate;

        if(type == 'R')
        {
		R_cntr++;
			switch(funct)
			{
				case add:
					buffer = r[rs] + r[rt];
					printf("add: r[rs] + r[rt] = buffer -> %d + %d = %d\n", r[rs], r[rt], buffer);
					break;
	
				case addu:
					buffer = r[rs] + r[rt];
                                        printf("addu: r[rs] + r[rt] = buffer -> %d + %d = %d\n", r[rs], r[rt], buffer);
					break;
	
				case and:
					buffer = r[rs] & r[rt];
                                        printf("and: r[rs] & r[rt] = buffer -> %d & %d = %d\n", r[rs], r[rt], buffer);
					break;
				
				case jr:
					PC = r[rs];
					printf("jr: PC: %X\n", PC);
					break;

				case nor:
					buffer = ~(r[rs] | r[rt]);
                                        printf("nor: ~(r[rs] | r[rt]) = buffer -> ~(%d | %d) = %d\n", r[rs], r[rt], buffer);
					break;

				case or:
					buffer = r[rs] | r[rt];
                                        printf("or: r[rs] | r[rt] = buffer -> %d | %d = %d\n", r[rs], r[rt], buffer);
					break;

				case slt:
					buffer = (r[rs] < r[rt])? 1 : 0;
                                        printf("slt: (r[rs] < r[rt])? 1 : 0) = buffer -> (%d < %d)? 1 : 0  = %d\n", r[rs], r[rt], buffer);
					break;

				case sltu:
					buffer = (r[rs] < r[rt])? 1 : 0;
                                        printf("sltu: (r[rs] < r[rt])? 1 : 0) = buffer -> (%d < %d)? 1 : 0  = %d\n", r[rs], r[rt], buffer);
					break;

				case sll:
					buffer = r[rt] << shamt;
					printf("sll: r[rt] shiftleft by shamt = buffer -> %X shiftleft by %d = %X\n", r[rt], shamt, buffer);
					break;

				case srl:
					buffer = r[rt] >> shamt;
                                        printf("srl: r[rt] shiftright by shamt = buffer -> %X shiftright by %d = %X\n", r[rt], shamt, buffer);
					break;

				case sub:
					buffer = r[rs] - r[rt];
                                        printf("sub: r[rs] - r[rt] = buffer -> %d - %d = %d\n", r[rs], r[rt], buffer);
					break;

				case subu:
					buffer = r[rs] - r[rt];
                                        printf("subu: r[rs] - r[rt] = buffer -> %d - %d = %d\n", r[rs], r[rt], buffer);
				break;
			}
        }

        else if(type == 'I')
        {
		I_cntr++;
			switch(opcode)
			{
				case addi:
					buffer = r[rs] + SignExtImm;
                                        printf("addi: r[rs] + SignExtImm = buffer -> %d - %d = %d\n", r[rs], SignExtImm, buffer);
					break;

				case addiu:
					buffer = r[rs] + SignExtImm;
                                        printf("addiu: r[rs] + SignExtImm = buffer -> %d - %d = %d\n", r[rs], SignExtImm, buffer);
					break;

				case andi:
					buffer = r[rs] & ZeroExtImm;
                                        printf("and: r[rs] & SignExtImm = buffer -> %d & %d = %d\n", r[rs], SignExtImm, buffer);
					break;

				case beq:
					branch_cntr++;
					if(r[rs] == r[rt]){
						PC = PC + 4 + BranchAddr;
						printf("beq: %d == %d PC = %X\n", r[rs], r[rt], PC);
					}else{
						PC = PC + 4;
                                                printf("beq: %d != %d PC = %X\n", r[rs], r[rt], PC);
					}
					break;

				case bne:
					branch_cntr++;
					if(r[rs] != r[rt]){
						PC = PC + 4 + BranchAddr;
                                                printf("bne: %d != %d PC = %X\n", r[rs], r[rt], PC);
					}
					else{
						PC = PC + 4;
                                                printf("bne: %d == %d PC = %X\n", r[rs], r[rt], PC);
					}
					break;
/*
				case lbu:

					break;

				case lhu:
					break;

				case ll:
					break;

				case lui:
					break;
*/
				case lw:
					buffer = memory[r[rs] + SignExtImm];
					printf("lw: memory[r[rs] + SignExtImm]=%X\tbuffer=%X\n",memory[r[rs] + SignExtImm], buffer);
					break;

				case ori:
					buffer = r[rs] | ZeroExtImm;
					printf("ori: r[rs] | ZeroExtImm = buffer -> %X | %X = %Xbuffer", r[rs], ZeroExtImm, buffer);
					break;

				case slti:
					buffer = (r[rs] < SignExtImm)? 1 : 0;
                                        printf("slti: (r[rs] < SignExtImm)? 1 : 0) = buffer -> (%d < %d)? 1 : 0  = %d\n", r[rs], r[rt], buffer);
					break;

				case sltiu:
					buffer = (r[rs] < SignExtImm)? 1 : 0;
                                        printf("sltiu: (r[rs] < SignExtImm)? 1 : 0) = buffer -> (%d < %d)? 1 : 0  = %d\n", r[rs], r[rt], buffer);
					break;
/*
				case sb:
					break;

				case sc:
					break;

				case sh:
					break;
*/
				case sw:
					memory[r[rs] + SignExtImm] = r[rt];
					printf("r[rt] = %X\tmemory[r[rs] + SignExtImm = %X\n", r[rt], memory[r[rs] + SignExtImm]);
					PC = PC + 4;
					break;
			}
        }

        else if(type == 'J')
        {
		J_cntr++;
			switch(opcode)
			{
				case j:
					PC = JumpAddr;
					printf("j: PC = %X\n", PC);
					break;

				case jal:
					r[31] = PC + 8;
					PC = JumpAddr;
					printf("jal: r[31]:%X\tPC:%X\n", r[31], PC);
					break;
			}
        }
		
        return;
}

void writeBack(void)
{
        if(type == 'R' && funct != jr)
	{
		r[rd] = buffer;
		printf("Writeback buffer:%d\tr[rd]:%d\n", buffer, r[rd]);
	}		
        
	else if(type == 'I' 
		&& opcode != beq
		&& opcode != bne
		&& opcode != sb
		&& opcode != sc
		&& opcode != sh
		&& opcode != sw)
		{
			r[rt] = buffer;
			printf("Writeback buffer:%d\tr[rt]:%d\n", buffer, r[rt]);
		}
	else if(type == 'J')		
		return;

	return;
}

void main(int argc, char *argv[])
{
	FILE *fptr = NULL;
	char *path = NULL;
	int readbuffer = 0;
	int cntr = 0;
	int cycle = 0;
	r[29] = 0x00800000;
	r[31] = 0xFFFFFFFF;
//detecting argv file path
	if (argc == 1)
        {
                printf("No file path detected\n");
                return;
        }
        else if (argc == 2)
        {
                path = argv[1];
        }
        else
        {
                printf("Too many arguments\n");
                return;
        }
        //open file
        fptr = fopen(path, "rb");
        if (fptr == NULL)
        {
                printf("Invalid input file: %s\n", path);
                return;
        }
        //putting file instruction to memory
	printf("Instruction inputs:\n");
        while (fread(&readbuffer, sizeof(readbuffer), 1, fptr) != 0)
        {
                memory[cntr] = (readbuffer & 0xFF) << 24
                        | (readbuffer & 0xFF00) << 8
                        | (readbuffer & 0xFF0000) >> 8
                        | (readbuffer & 0xFF000000) >> 24;
		printf("%d: %X\n", cntr, memory[cntr]);
		cntr++;
        }
        //datapath
        while (PC != 0xFFFFFFFF && (PC/4)<=cntr)
      	{
		cycle++;
		printf("----------Cycle %d----------\n", cycle);
		fetch();
		printf("Instruction: %X\tPC: %d\tType: %c\n", instruction, PC,  type);
		decode();
		execute();
		writeBack();
	
		if(type != 'J'
			&& opcode != beq
			&& opcode != bne
			&& funct != jr
			&& opcode != sw)
			PC = PC + 4;

		printf("r[2]: %d\n", r[2]);
        }
	printf("------------------------------\n\nEnd Result R2:%d, J-type:%d, R-type:%d, I-type:%d, #Branch:%d\n", r[2], J_cntr, R_cntr, I_cntr, branch_cntr);
        fclose(fptr);
        return;
}
