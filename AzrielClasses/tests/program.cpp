#include <iostream>

using namespace std;
int main (){
	int x = sizeof(int);
	cout << x << endl;;
	x=sizeof(long);
	cout << x << endl;;
	x=sizeof(long long);
	cout << x << endl;;
	x=sizeof(float);
	cout << x << endl;;
	x=sizeof(double);
	cout << x << endl;;
		x=sizeof(char);
	cout << x << endl;;
		x=sizeof(short);
	cout << x << endl;;
	system("pause");
	return 0;
}