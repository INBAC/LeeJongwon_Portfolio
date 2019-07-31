#include<stdio.h>
#include<stdlib.h>
#include<string.h>

int main(void)
{
	FILE *fileptr = fopen("./input.txt", "r");
	int cntr = 0;
	int lastline = 0;
	int regnum1 = 0;
	int regnum2 = 0;
	char **readarray = NULL;
	char*line = NULL;
	char operator = ' ';
	char *operand1 = NULL;
	char *operand2 = NULL;
	size_t buffersize = 32;
	float R[10] = { 0 };
	float operandnum[2] = { 0 };

	readarray = malloc(buffersize*sizeof(char*));
	while ((getline(&line, &buffersize, fileptr)) != -1)
	{
		readarray[cntr] = malloc(strlen(line) + 1);
		strcpy(readarray[cntr], line);
		cntr++;
	}
	lastline = --cntr;
	cntr = 0;

	while (cntr <= lastline)
	{
		strcpy(line, readarray[cntr]);
		operator = line[0];
		switch (operator)
		{
			case '+'://adds
				operand1 = strtok(line, "+ ");
				operand2 = strtok(NULL, " \n");
				if (operand1[0] == '0')
				{
					operandnum[0] = atof(operand1 + 2);
					if (operand2[0] == '0')
					{
						operandnum[1] = atof(operand2 + 2);
						R[0] = operandnum[0] + operandnum[1];
						printf("%s\tR0: 0x%0.1f + 0x%0.1f = %0.1f\n\n", readarray[cntr], operandnum[0], operandnum[1], R[0]);	
					}
					else if (operand2[0] == 'R')
					{
						regnum1 = atoi(operand2 + 1);
						R[0] = operandnum[0] + R[regnum1];
						printf("%s\tR0: 0x%0.1f + R%d = %0.1f\n\n", readarray[cntr], operandnum[0], regnum1, R[0]);
					}
				}
				else if (operand1[0] == 'R')
				{
					regnum1 = atoi(operand1 + 1);
					if (operand2[0] == '0')
					{
						operandnum[1] = atof(operand2 + 2);
						R[0] = R[regnum1] + operandnum[1];
						printf("%s\tR0: R%d + 0x%0.1f = %0.1f\n\n", readarray[cntr], regnum1, operandnum[1], R[0]);
					}
					else if (operand2[0] == 'R')
					{
						regnum2 = atoi(operand2 + 1);
						R[0] = R[regnum1] + R[regnum2];
						printf("%s\tR0: R%d + R%d = %0.1f\n\n", readarray[cntr], regnum1, regnum2, R[0]);
					}
				}
				break;

			case '-'://subtracts
				operand1 = strtok(line, "- ");
				operand2 = strtok(NULL, " \n");
				if (operand1[0] == '0')
				{
					operandnum[0] = atof(operand1 + 2);
					if (operand2[0] == '0')
					{
						operandnum[1] = atof(operand2 + 2);
						R[0] = operandnum[0] - operandnum[1];
						printf("%s\tR0: 0x%0.1f - 0x%0.1f = %0.1f\n\n", readarray[cntr], operandnum[0], operandnum[1], R[0]);
					}
					else if (operand2[0] == 'R')
					{
						regnum1 = atoi(operand2 + 1);
						R[0] = operandnum[0] - R[regnum1];
						printf("%s\tR0: 0x%0.1f - R%d = %0.1f\n\n", readarray[cntr], operandnum[0], regnum1, R[0]);
					}
				}
				else if (operand1[0] == 'R')
				{
					regnum1 = atoi(operand1 + 1);
					if (operand2[0] == '0')
					{
						operandnum[1] = atof(operand2 + 2);
						R[0] = R[regnum1] - operandnum[1];
						printf("%s\tR0: R%d - 0x%0.1f = %0.1f\n\n", readarray[cntr], regnum1, operandnum[1], R[0]);
					}
					else if (operand2[0] == 'R')
					{
						regnum2 = atoi(operand2 + 1);
						R[0] = R[regnum1] - R[regnum2];
						printf("%s\tR0: R%d - R%d = %0.1f\n\n", readarray[cntr], regnum1, regnum2, R[0]);
					}
				}
				break;

			case '*'://multiplies
				operand1 = strtok(line, "* ");
				operand2 = strtok(NULL, " \n");
				if (operand1[0] == '0')
				{
					operandnum[0] = atof(operand1 + 2);
					if (operand2[0] == '0')
					{
						operandnum[1] = atof(operand2 + 2);
						R[0] = operandnum[0] * operandnum[1];
						printf("%s\tR0: 0x%0.1f * 0x%0.1f = %0.1f\n\n", readarray[cntr], operandnum[0], operandnum[1], R[0]);
					}
					else if (operand2[0] == 'R')
					{
						regnum1 = atoi(operand2 + 1);
						R[0] = operandnum[0] * R[regnum1];
						printf("%s\tR0: 0x%0.1f * R%d = %0.1f\n\n", readarray[cntr], operandnum[0], regnum1, R[0]);
					}
				}
				else if (operand1[0] == 'R')
				{
					regnum1 = atoi(operand1 + 1);
					if (operand2[0] == '0')
					{
						operandnum[1] = atof(operand2 + 2);
						R[0] = R[regnum1] * operandnum[1];
						printf("%s\tR0: R%d * 0x%0.1f = %0.1f\n\n", readarray[cntr], regnum1, operandnum[1], R[0]);
					}
					else if (operand2[0] == 'R')
					{
						regnum2 = atoi(operand2 + 1);
						R[0] = R[regnum1] * R[regnum2];
						printf("%s\tR0: R%d * R%d = %0.1f\n\n", readarray[cntr], regnum1, regnum2, R[0]);
					}
				}
				break;

			case '/'://divides
				operand1 = strtok(line, "/ ");
				operand2 = strtok(NULL, " \n");
				if (operand1[0] == '0')
				{
					operandnum[0] = atof(operand1 + 2);
					if (operand2[0] == '0')
					{
						operandnum[1] = atof(operand2 + 2);
						if (operandnum[1] == 0)
						{
							printf("%s\tR0: 0x%0.1f / 0x%0.1f = Can not divide by 0\n\n", readarray[cntr], operandnum[0], operandnum[1]);
						}
						else
						{
							R[0] = operandnum[0] / operandnum[1];
							printf("%s\tR0: 0x%0.1f / 0x%0.1f = %0.1f\n\n", readarray[cntr], operandnum[0], operandnum[1], R[0]);
						}
					}
					else if (operand2[0] == 'R')
					{
						regnum1 = atoi(operand2 + 1);
						if (R[regnum1] == 0)
						{
							printf("%s\tR0: 0x%0.1f / R%d = Can not divide by 0\n\n", readarray[cntr], operandnum[0], regnum1);
						}
						else
						{
							R[0] = operandnum[0] / R[regnum1];
							printf("%s\tR0: 0x%0.1f / R%d = %0.1f\n\n", readarray[cntr], operandnum[0], regnum1, R[0]);
						}
					}
				}
				else if (operand1[0] == 'R')
				{
					regnum1 = atoi(operand1 + 1);
					if (operand2[0] == '0')
					{
						operandnum[1] = atof(operand2 + 2);
						if (operandnum[1] == 0)
						{
							printf("%s\tR0: R%d / 0x%0.1f = Can not divide by 0\n\n", readarray[cntr], regnum1, operandnum[1]);
						}
						else
						{
							R[0] = R[regnum1] / operandnum[1];
							printf("%s\tR0: R%d / 0x%0.1f = %0.1f\n\n", readarray[cntr], regnum1, operandnum[1], R[0]);
						}
					}
					else if (operand2[0] == 'R')
					{
						regnum2 = atoi(operand2 + 1);
						if (R[regnum2] == 0)
						{
							printf("%s\tR0: R%d / R%d = Can not divide by 0\n\n", readarray[cntr], regnum1, regnum2);
						}
						else
						{
							R[0] = R[regnum1] / R[regnum2];
							printf("%s\tR0: R%d / R%d = %0.1f\n\n", readarray[cntr], regnum1, regnum2, R[0]);
						}
					}
				}
				break;

			case 'M'://move value to right operator
				operand1 = strtok(line, "M ");
				operand2 = strtok(NULL, " \n");
				if (operand1[0] == 'R')
				{
					regnum1 = atoi(operand1 + 1);
					if (operand2[0] == '0')
					{
						operandnum[1] = atof(operand2 + 2);
						R[regnum1] = operandnum[1];
						printf("%s\tR%d: 0x%0.1f\n\n", readarray[cntr], regnum1, operandnum[1]);
					}
					else
					{
						regnum2 = atoi(operand2 + 2);
						R[regnum1] = R[regnum2];
						printf("%s\tR%d: R%d\n\n", readarray[cntr], regnum1, regnum2);
					}
				}
				else
				{
					printf("%s\tDestination needs to be a Register\n\n", line);
				}

				break;

			case 'C'://R0 = 0 if operand1 >= operand2, R0 = 1 if operand1 < operand2
				operand1 = strtok(line, "C ");
				operand2 = strtok(NULL, " \n");
				if (operand1[0] == '0')
				{
					operandnum[0] = atof(operand1 + 2);
					if (operand2[0] == '0')
					{
						operandnum[1] = atof(operand2 + 2);
						if (operandnum[0] >= operandnum[1])
						{
							R[0] = 0;
							printf("%s\t0x%0.1f >= 0x%0.1f\n\tR0: 0\n\n", readarray[cntr], operandnum[0], operandnum[1]);
						}
						else
						{
							R[0] = 1;
							printf("%s\t0x%0.1f < 0x%0.1f\n\tR0: 1\n\n", readarray[cntr], operandnum[0], operandnum[1]);
						}
					}
					else if (operand2[0] == 'R')
					{
						regnum1 = atoi(operand2 + 1);
						if (operandnum[0] >= R[regnum1])
						{
							R[0] = 0;
							printf("%s\t0x%0.1f >= R%d\n\tR0: 0\n\n", readarray[cntr], operandnum[0], regnum1);
						}
						else
						{
							R[0] = 1;
							printf("%s\t0x%0.1f < R%d\n\tR0: 1\n\n", readarray[cntr], operandnum[0], regnum1);
						}
					}
				}
				else if (operand1[0] == 'R')
				{
					regnum1 = atoi(operand1 + 1);
					if (operand2[0] == '0')
					{
						operandnum[1] = atof(operand2 + 2);
						if (R[regnum1] >= operandnum[1])
						{
							R[0] = 0;
							printf("%s\tR%d >= 0x%0.1f\n\tR0: 0\n\n", readarray[cntr], regnum1, operandnum[1]);
						}
						else
						{
							R[0] = 1;
							printf("%s\tR%d < 0x%0.1f\n\tR0: 1\n\n", readarray[cntr], regnum1, operandnum[1]);
						}
					}
					else if (operand2[0] == 'R')
					{
						regnum2 = atoi(operand2 + 1);
						if (R[regnum1] >= R[regnum2])
						{
							R[0] = 0;
							printf("%s\tR%d >= R%d\n\tR0: 0\n\n", readarray[cntr], regnum1, regnum2);
						}
						else
						{
							R[0] = 1;
							printf("%s\tR%d < R%d\n\tR0: 1\n\n", readarray[cntr], regnum1, regnum2);
						}
					}
				}
				break;

			case 'J'://jumps to operand line
				operand1 = strtok(line, "J\n ");
				cntr = atoi(operand1 + 2);
				printf("%s\t Jump to line %d\n\n", readarray[cntr], cntr);
				cntr = cntr - 2;
				break;

			case 'B'://if R0 is 1 jump to operand line
				if (R[0] == 1)
				{
					operand1 = strtok(line, "B\n ");
					cntr = atoi(operand1 + 2);
					printf("%s\tJump to line %d\n\n", readarray[cntr], cntr);
					cntr = cntr - 2;
				}
				else
				{
					printf("%s\tR0 does not equal 1\n\n", readarray[cntr]);
				}
				break;

			case 'H'://halt
				printf("Prgramm Halt\n");
				return(0);

			default:
				printf("%s\t Opcode not recognized\n\n", readarray[cntr]);
				break;
		}
		cntr++;
	}

	fclose(fileptr);
	return 0;
}
