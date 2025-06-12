#include "AzrielFunctions.h"
using namespace std;

string UintToString(unsigned num) {
	string str = "xxxx";
	*(unsigned *)str.data() = num;
	return str;
}
unsigned StringToUint(const string & str) {
	return *(unsigned *)str.data();
}

