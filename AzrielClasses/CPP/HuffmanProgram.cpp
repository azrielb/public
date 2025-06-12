#include <iostream>
#include <fstream> 
#include "HuffmanTree.h"
#include "AzrielFunctions.h"
using namespace std;

void encodeInto(string text, ostream& out);
string decodeFrom(istream& in);

int HuffmanMain() {
	if (false|| true) {
		//ifstream inputfile();
		HuffmanTree Huffman = HuffmanTree();
		string text;
		cout << "enter text: ";
		cin >> text;
		if (text == "clip") text = "eeddddddddcccccccccbbbbbbbbbbbbaaaaaaaaaaaaaa";
		cout << '.' << text << '.'<< endl;
		text = Huffman.encodeText(text);
		cout << '.' << text << '.'<< endl;
		cout <<'.'<< Huffman.decodeText(text) << '.'<<endl;
		system("pause");
	return 0;
	}

	string text = "";
	unsigned long long srcSize ,codedSize;
	{
		ifstream inFile("original.txt", ios::in | ios::binary);
		if (inFile.fail()) throw "the original file does not exists!";
		inFile.seekg(0, ios::end);
		srcSize = inFile.tellg();
		inFile.seekg(0, ios::beg);
		text = string((unsigned)srcSize, ' ');
		inFile.read((char *)(text.data()), srcSize);
		inFile.close();
	}

	{
		ofstream codedFile("coded.txt", ios::out | ios::binary);
		if (codedFile.fail()) throw "can't create coded file!";
		encodeInto(text, codedFile);
		codedFile.seekp(0, ios::end);
		codedSize = codedFile.tellp();
		codedFile.close();
	}

	{
		ifstream codedFile("coded.txt", ios::in | ios::binary);
		if (codedFile.fail()) throw "the coded file does not exists!";
		text = decodeFrom(codedFile);
		codedFile.close();
	}

	{
		ofstream decodedFile("decoded.txt", ios::out | ios::binary);
		if (decodedFile.fail()) throw "can't create decoded file!";
		decodedFile.write(text.data(), text.length());
		decodedFile.close();
	}
	cout <<endl<< ((float)codedSize) / srcSize << endl;

	cout <<endl;
	system ("pause");
	return 0;
}

void encodeInto(string text, ostream& out){
	HuffmanTree Huffman = HuffmanTree();
	out << Huffman.encodeText(text);
}
string decodeFrom(istream& in) {
	in.seekg(0,ios::end);
	unsigned long long size = in.tellg();
		in.seekg(0, ios::beg);
	string coded((unsigned)size, 'l');
	in.read((char *)(coded.data()), size);
	HuffmanTree Huffman = HuffmanTree();
	return Huffman.decodeText(coded);
}
int main(){
	return HuffmanMain();
}
