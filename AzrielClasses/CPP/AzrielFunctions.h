#pragma once
#include <string>


template<class T> // where T has the operator ">"
void boubleSort(T * arr[], int size) {
	bool swaped;
	int a = size * size * 2;
	do {
		swaped = false;
		for (int i = 1; i < size; ++i)
			if (*(arr[i - 1])> *(arr[i])) {
				swap(arr[i - 1], arr[i]);
				swaped = true;
			}
	} while (swaped && --a > 0);
}

std::string UintToString(unsigned num);
unsigned StringToUint(const std::string & str);
