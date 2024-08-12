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
This appears to downgrade the processor to a very old/od intel processor

#### Intel CPUID Values. 

```text
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

```
