#pragma once
#include <string>
using namespace std;
class HuffmanNode
{
private:
	char text;
	unsigned frequency;
	HuffmanNode * left;
	HuffmanNode * right;
public:
	HuffmanNode(char t, unsigned f); // constructor for a leaf
	HuffmanNode(HuffmanNode * l = NULL, HuffmanNode * r = NULL); // constructor for internal HuffmanNode (not a leaf)
	virtual ~HuffmanNode();

	//properties
	HuffmanNode * getLeft();
	HuffmanNode * getRight();
	char getLetter();
	unsigned getFrequency();

	//comparing function - compare by frequency
	bool operator== (const HuffmanNode & other) const;
	bool operator!= (const HuffmanNode & other) const;
	bool operator<(const HuffmanNode & other) const;
	bool operator>(const HuffmanNode & other) const;
	bool operator<=(const HuffmanNode & other) const;
	bool operator>=(const HuffmanNode & other) const;
};
