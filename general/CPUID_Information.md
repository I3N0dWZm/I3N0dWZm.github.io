### CPUID Information
### 12-08-24

Recently i was asked if its possible to create MAC OS as a VMware Virtual Machine, turns out it is! , but you have to modyf the .vmx file to force it to run with the folloing config changes

#### Changes to .vmx file FOR VMware.
```text
cpuid.0.eax = "0000:0000:0000:0000:0000:0000:0000:1011"
cpuid.0.ebx = "0111:0101:0110:1110:0110:0101:0100:0111"
cpuid.0.ecx = "0110:1100:0110:0101:0111:0100:0110:1110"
cpuid.0.edx = "0100:1001:0110:0101:0110:1110:0110:1001"
cpuid.1.eax = "0000:0000:0000:0001:0000:0110:0111:0001"
cpuid.1.ebx = "0000:0010:0000:0001:0000:1000:0000:0000"
cpuid.1.ecx = "1000:0010:1001:1000:0010:0010:0000:0011"
cpuid.1.edx = "0000:0111:1000:1011:1111:1011:1111:1111"
```
This appears to downgrade the processor to a very old/odd intel processor

#### Intel CPUID Values. 

| Bit | cpuid0.eax        | cpuid0.ebx | cpuid0.ecx | cpuid0.edx | cpuid1.eax                | cpuid1.ebx            | cpuid1.ecx       | cpuid1.edx  |
|-----|-------------------|------------|------------|------------|---------------------------|-----------------------|------------------|-------------|
| 0   | Max CPUID input    | 1          | 0          | 1          | Stepping ID (3:0)         | Brand Index (7:0)     | SSE3             | FPU         |
| 1   | Max CPUID input    | 1          | 1          | 0          | Stepping ID (3:0)         | Brand Index (7:0)     | PCLMULQDQ        | VME         |
| 2   | Max CPUID input    | 1          | 1          | 0          | Stepping ID (3:0)         | Brand Index (7:0)     | DTES64           | DE          |
| 3   | Max CPUID input    | 0          | 1          | 1          | Stepping ID (3:0)         | Brand Index (7:0)     | MONITOR          | PSE         |
| 4   |                   | 0          | 0          | 0          | Model (7:4)               | Brand Index (7:0)     | DS-CPL           | TSC         |
| 5   |                   | 0          | 1          | 1          | Model (7:4)               | Brand Index (7:0)     | VMX              | MSR         |
| 6   |                   | 1          | 1          | 1          | Model (7:4)               | Brand Index (7:0)     | SMX              | PAE         |
| 7   |                   | 0          | 0          | 0          | Model (7:4)               | Brand Index (7:0)     | EIST             | MCE         |
| 8   |                   | 1          | 0          | 0          | Family (11:8)             | CLFLUSH Line Size (15:8) | TM2           | CX8         |
| 9   |                   | 0          | 0          | 1          | Family (11:8)             | CLFLUSH Line Size (15:8) | SSSE3         | APIC        |
| 10  |                   | 1          | 1          | 1          | Family (11:8)             | CLFLUSH Line Size (15:8) | CNXT-ID       | Reserved    |
| 11  |                   | 0          | 0          | 1          | Family (11:8)             | CLFLUSH Line Size (15:8) | SDBG          | SEP         |
| 12  |                   | 0          | 1          | 0          | Processor Type (13:12)    | CLFLUSH Line Size (15:8) | FMA           | MTRR        |
| 13  |                   | 1          | 1          | 1          | Processor Type (13:12)    | CLFLUSH Line Size (15:8) | CMPXCHG16B    | PGE         |
| 14  |                   | 1          | 1          | 1          | Reserved                  | CLFLUSH Line Size (15:8) | xTPR Update Control | MCA   |
| 15  |                   | 0          | 0          | 1          | Reserved                  | CLFLUSH Line Size (15:8) | PDCM          | CMOV        |
| 16  |                   | 0          | 1          | 1          | Extended Model (19:16)    | Max Logical Processors (23:16) | PCID     | PAT         |
| 17  |                   | 1          | 0          | 0          | Extended Model (19:16)    | Max Logical Processors (23:16) | DCA      | PSE-36      |
| 18  |                   | 1          | 1          | 1          | Extended Model (19:16)    | Max Logical Processors (23:16) | SSE4.1   | PSN         |
| 19  |                   | 1          | 0          | 0          | Extended Model (19:16)    | Max Logical Processors (23:16) | SSE4.2   | CLFSH       |
| 20  |                   | 0          | 0          | 0          | Extended Family (27:20)   | Max Logical Processors (23:16) | x2APIC   | Reserved    |
| 21  |                   | 1          | 1          | 1          | Extended Family (27:20)   | Max Logical Processors (23:16) | MOVBE    | DS          |
| 22  |                   | 1          | 1          | 1          | Extended Family (27:20)   | Max Logical Processors (23:16) | POPCNT   | ACPI        |
| 23  |                   | 0          | 0          | 0          | Extended Family (27:20)   | Max Logical Processors (23:16) | TSC-Deadline | MMX    |
| 24  |                   | 1          | 0          | 1          | Extended Family (27:20)   | Initial APIC ID (31:24)       | AES      | FXSR        |
| 25  |                   | 0          | 0          | 0          | Extended Family (27:20)   | Initial APIC ID (31:24)       | XSAVE    | SSE         |
| 26  |                   | 1          | 1          | 0          | Extended Family (27:20)   | Initial APIC ID (31:24)       | OSXSAVE  | SSE2        |
| 27  |                   | 0          | 1          | 1          | Extended Family (27:20)   | Initial APIC ID (31:24)       | AVX      | Reserved    |
| 28  |                   | 1          | 0          | 0          | Reserved                  | Initial APIC ID (31:24)       | F16C     | Reserved    |
| 29  |                   | 1          | 1          | 0          | Reserved                  | Initial APIC ID (31:24)       | RDRAND   | Reserved    |
| 30  |                   | 1          | 1          | 1          | Reserved                  | Initial APIC ID (31:24)       | Reserved | Reserved    |
| 31  |                   | 0          | 0          | 0          | Reserved                  | Initial APIC ID (31:24)       | Hypervisor present | Reserved |




Check your own CPUID values of your PC
Compile with 'cl /MD cpu_details.c'

```c
#include <stdio.h>
#include <intrin.h>

// Function to print a 32-bit value as a binary string with the specified format
void print_formatted_binary(const char* label, unsigned int value) {
    printf("%s", label);
    printf(" = \"");

    // Print the binary representation of the value
    for (int i = 31; i >= 0; --i) {
        printf("%c", (value & (1 << i)) ? '1' : '0');
        // Print ':' every 4 bits but not after the last set of 4 bits
        if (i % 4 == 0 && i != 0) {
            printf(":");
        }
    }

    printf("\"\n");
}

// Function to print register values with the custom format for a specific CPUID level
void printregs(int level, int eax, int ebx, int ecx, int edx) {
    char label[50]; // Buffer for label

    // Print each register with the appropriate format
    snprintf(label, sizeof(label), "cpuid.%d.eax", level);
    print_formatted_binary(label, eax);

    snprintf(label, sizeof(label), "cpuid.%d.ebx", level);
    print_formatted_binary(label, ebx);

    snprintf(label, sizeof(label), "cpuid.%d.ecx", level);
    print_formatted_binary(label, ecx);

    snprintf(label, sizeof(label), "cpuid.%d.edx", level);
    print_formatted_binary(label, edx);
}

// Function to get the processor name
void get_processor_name(char* name, size_t size) {
    int cpuInfo[4];

    // Get the processor name in parts
    __cpuid(cpuInfo, 0x80000002);
    memcpy(name, cpuInfo, sizeof(cpuInfo));

    __cpuid(cpuInfo, 0x80000003);
    memcpy(name + 16, cpuInfo, sizeof(cpuInfo));

    __cpuid(cpuInfo, 0x80000004);
    memcpy(name + 32, cpuInfo, sizeof(cpuInfo));

    // Ensure the string is null-terminated
    name[size - 1] = '\0';
}

int main(void) {
    int cpuInfo[4];
    char processorName[49]; // Buffer for the processor name, considering null terminator

    // Get and print the processor name
    get_processor_name(processorName, sizeof(processorName));
    printf("Processor Name: %s\n\n", processorName);

    // CPUID with eax = 0
    __cpuid(cpuInfo, 0);
    printregs(0, cpuInfo[0], cpuInfo[1], cpuInfo[2], cpuInfo[3]);
    printf("\n");

    // CPUID with eax = 1
    __cpuid(cpuInfo, 1);
    printregs(1, cpuInfo[0], cpuInfo[1], cpuInfo[2], cpuInfo[3]);
    printf("\n");

    return 0;
}
```

examples:
```text
Processor Name: Intel(R) Core(TM) i3-7100 CPU @ 3.90GHz

cpuid.0.eax = "0000:0000:0000:0000:0000:0000:0001:0110"
cpuid.0.ebx = "0111:0101:0110:1110:0110:0101:0100:0111"
cpuid.0.ecx = "0110:1100:0110:0101:0111:0100:0110:1110"
cpuid.0.edx = "0100:1001:0110:0101:0110:1110:0110:1001"
cpuid.1.eax = "0000:0000:0000:1001:0000:0110:1110:1001"
cpuid.1.ebx = "0000:0001:0001:0000:0000:1000:0000:0000"
cpuid.1.ecx = "0111:1111:1111:1010:1111:1011:1011:1111"
cpuid.1.edx = "1011:1111:1110:1011:1111:1011:1111:1111"

Processor Name: Intel(R) Core(TM) i3-1005G1 CPU @ 1.20GHz

cpuid.0.eax = "0000:0000:0000:0000:0000:0000:0001:1011"
cpuid.0.ebx = "0111:0101:0110:1110:0110:0101:0100:0111"
cpuid.0.ecx = "0110:1100:0110:0101:0111:0100:0110:1110"
cpuid.0.edx = "0100:1001:0110:0101:0110:1110:0110:1001"
cpuid.1.eax = "0000:0000:0000:0111:0000:0110:1110:0101"
cpuid.1.ebx = "0000:0010:0001:0000:0000:1000:0000:0000"
cpuid.1.ecx = "1111:1111:1101:1010:1111:0011:1000:1111"
cpuid.1.edx = "1011:1111:1110:1011:1111:1011:1111:1111"

Processor Name: AMD Ryzen 5 2600 Six-Core Processor            

cpuid.0.eax = "0000:0000:0000:0000:0000:0000:0000:1101"
cpuid.0.ebx = "0110:1000:0111:0100:0111:0101:0100:0001"
cpuid.0.ecx = "0100:0100:0100:1101:0100:0001:0110:0011"
cpuid.0.edx = "0110:1001:0111:0100:0110:1110:0110:0101"
cpuid.1.eax = "0000:0000:1000:0000:0000:1111:1000:0010"
cpuid.1.ebx = "0000:0100:0000:1100:0000:1000:0000:0000"
cpuid.1.ecx = "1111:1110:1101:1000:0011:0010:0000:1011"
cpuid.1.edx = "0001:0111:1000:1011:1111:1011:1111:1111"
```


















