### Hack The Box - LFI - enumuration over /proc

Whilst the path can change for local file inclusion the majority of the time its the same files your interested in.

/proc/<number>/cmdline - this path can be very helpful wehn you havnt yest got terminal access to a box, it may be possible to see whats processes are running on the box with local file inclusion if the vurnrability exists on the box.
  


